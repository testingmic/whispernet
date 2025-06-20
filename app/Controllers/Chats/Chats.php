<?php

namespace App\Controllers\Chats;

use Exception;
use Config\Encryption;
use App\Controllers\LoadController;
use App\Libraries\Routing;

class Chats extends LoadController {

    public $selfDestruct = 24;
    public $encrypter;

    /**
     * Create chat room
     * 
     * @return array
     */
    public function createChatRoom() {
        return $this->chatsModel->createChatRoom($this->payload['userId']);
    }

    /**
     * Add participant
     * 
     * @return array
     */
    public function addParticipant() {
        return $this->chatsModel->addParticipant($this->payload['roomId'], $this->payload['userId'], $this->payload['addedByUserId']);
    }

    /**
     * Remove participant
     * 
     * @return array
     */
    public function removeParticipant() {
        return $this->chatsModel->removeParticipant($this->payload['roomId'], $this->payload['userId'], $this->payload['removedByUserId']);
    }

    /**
     * Get the encrypter object
     * 
     * @return void
     */
    private function getEncrypter($roomId) {
        // get the encryption object
        $config         = config(Encryption::class);
        $config->key    = "{$roomId}_Ienc{$roomId}_KSrIt";
        $config->driver = "OpenSSL";
        
        // get the encryption object
        $this->encrypter = service('encrypter', $config);
    }

    /**
     * Send message
     * 
     * @return array
     */
    public function send() {

        // check if the sender is the current user
        $this->payload['sender'] = $this->currentUser['user_id'];
        $this->payload['timestamp'] = !empty($this->payload['timestamp']) ? $this->payload['timestamp'] : time();

        // check if the sender is the receiver
        if($this->payload['sender'] == $this->payload['receiver']) {
            return Routing::error('You cannot send a message to yourself');
        }

        // check if the type is set
        $this->payload['type'] = !empty($this->payload['type']) ? $this->payload['type'] : 'individual';

        // check the chat type
        $isIndividual = (bool)($this->payload['type'] == 'individual');
        if(empty($this->payload['roomId'])) {
            $room = $isIndividual ? $this->chatsModel->getIndividualChatRoomId($this->payload['sender'], $this->payload['receiver']) : [];
            if(!empty($room) && ((int)$room['sender_deleted'] == 1)) {
                return Routing::error('Chat room not found');
            }

            // if the chat room is not found, create a new one
            if(empty($room)) {
                $room = $this->chatsModel->createChatRoom($this->payload['sender'], $this->payload['receiver'], $this->payload['type'], [
                    (int)$this->payload['sender'],
                    (int)$this->payload['receiver']
                ]);
            }

        } else {
            $roomId = $this->payload['roomId'];
            $room  = $this->chatsModel->getChatRoom($roomId);
            if(empty($room)) {
                return Routing::error('Chat room not found');
            }

            // check if the sender is a participant of the chat
            if(!in_array($this->payload['sender'], json_decode($room['receipients_list'], true))) {
                return Routing::error('You are not a participant of this chat');
            }
        }

        // get the room id
        $theRoomId = $room['room_id'] ?? $room;

        // get the self destruct time
        $selfDestruct  = time() + ($this->selfDestruct * 60);

        $this->getEncrypter($theRoomId);
        
        // encrypt the message
        $encryptData = $this->encrypter->encrypt($this->payload['message']);

        // generate a unique message id
        $messageUUID = generateUUID();

        $payload = [
            'room_id' => $theRoomId,
            'is_encrypted' => 1,
            'unique_id' => $messageUUID,
            'user_id' => $this->payload['sender'],
            'content' => base64_encode($encryptData),
            'self_destruct_at' => $selfDestruct
        ];

        return Routing::created(['data' => 'Message sent successfully', 'record' => [
            'roomId' => $theRoomId,
            'userId' => $this->payload['sender'],
            'messageId' => $this->chatsModel->postMessage($payload)
        ]]);

    }

    /**
     * Get messages
     * 
     * @return array
     */
    public function messages() {
        
        // check if the room id or receiver id is set
        if(empty($this->payload['roomId']) && empty($this->payload['receiverId'])) {
            return Routing::error('Room ID or receiver ID is required');
        }

        $senderId = $this->currentUser['user_id'];
        $receiverId = $this->payload['receiverId'];
        
        if(!empty($this->payload['roomId'])) {
            $room  = $this->chatsModel->getChatRoom($this->payload['roomId']);
            if(empty($room)) {
                return Routing::error('Chat room not found');
            }
        }

        else {
            $room = $this->chatsModel->getIndividualChatRoomId($senderId, $receiverId);
            if(empty($room)) {
                return Routing::error('Chat room not found');
            }
        }

        $allowed = json_decode($room['receipients_list'], true);
        if(!in_array($senderId, $allowed)) {
            return Routing::error('You are not a participant of this chat');
        }

        // get the encrypter object
        $this->getEncrypter($room['room_id']);

        // get the messages
        $messages = $this->chatsModel->getMessages($room['room_id'], $this->payload['offset'], $this->payload['limit']);
        
        $allowedMessages = [];

        // remove unwanted messages from list
        foreach($messages as $key => $message) {
            // check if the message is self destructing
            if(!empty($message['self_destruct_at']) && time() > $message['self_destruct_at']) continue;

            $append = false;

            // check if the message is from the sender
            if(($message['user_id'] == $senderId) && (int)$message['sender_deleted'] !== 1) {
                $append = true;
                $type = "sent";
            }

            if(($message['user_id'] == $receiverId) && (int)$message['receiver_deleted'] !== 1) {
                $append = true;
                $type = "received";
            }

            if($append) {
                $allowedMessages[] = [
                    'msgid' => $message['message_id'],
                    'message' => $this->encrypter->decrypt(base64_decode($message['content'])),
                    'sender' => $message['user_id'],
                    'time' => date('h:i A', strtotime($message['created_at'])),
                    'uuid' => $message['unique_id'],
                    'created_at' => $message['created_at'],
                    'type' => $type,
                ];
            }
        }


        return Routing::success(array_reverse($allowedMessages));


    }

    /**
     * Get user chats
     * 
     * @return array
     */
    public function getUserChats() {
        return $this->chatsModel->getUserChats($this->payload['userId'], $this->payload['page'], $this->payload['limit']);
    }

    /**
     * Get chat participants
     * 
     * @return array
     */
    public function getChatParticipants() {
        return $this->chatsModel->getChatParticipants($this->payload['roomId'], $this->payload['userId']);
    }
} 