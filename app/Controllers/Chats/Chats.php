<?php

namespace App\Controllers\Chats;

use Exception;
use Config\Encryption;
use App\Controllers\LoadController;
use App\Libraries\Routing;
use App\Controllers\Media\Media;

class Chats extends LoadController {

    public $selfDestruct = 24;
    public $encrypter;

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
     * Get user chat rooms
     * 
     * @return array
     */
    public function rooms() {

        // get the user chat rooms
        $chatRooms = $this->chatsModel->getUserChatRooms($this->currentUser['user_id']);

        // group the list by groups and individuals
        $newRooms = [
            'individual' => [],
            'group' => [],
        ];
        
        foreach($chatRooms as $room) {
            $newRooms[$room['room']['type']][] = $room;
        }

        return Routing::success($newRooms);
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

        // trim the message
        $this->payload['message'] = !empty($this->payload['message']) ? trim($this->payload['message']) : '';

        // check if the message or media is set
        if(empty($this->payload['message']) && empty($this->payload['file_uploads'])) {
            return Routing::error('Message or media is required');
        }

        // check if the type is set
        $this->payload['type'] = !empty($this->payload['type']) ? $this->payload['type'] : 'individual';

        // check the chat type
        $isIndividual = (bool)($this->payload['type'] == 'individual');
        if(empty($this->payload['roomId']) && $isIndividual) {
            $room = $isIndividual ? $this->chatsModel->getIndividualChatRoomId((int)$this->payload['sender'], (int)$this->payload['receiver']) : [];
            if(!empty($room) && ((int)$room['sender_deleted'] == 1)) {
                return Routing::error('Chat room not found');
            }

            // if the chat room is not found, create a new one
            if(empty($room)) {
                $room = $this->chatsModel->createChatRoom((int)$this->payload['sender'], (int)$this->payload['receiver'], $this->payload['type'], [
                    (int)$this->payload['sender'],
                    (int)$this->payload['receiver']
                ]);
            }

        } else {
            $roomId = (int)$this->payload['roomId'];
            $room  = $this->chatsModel->getChatRoom($roomId);
            if(empty($room)) {
                return Routing::error('Chat room not found');
            }

            // check if the sender is a participant of the chat
            if(!in_array((int)$this->payload['sender'], json_decode($room['receipients_list'], true))) {
                return Routing::error('You are not a participant of this chat');
            }
        }

        // get the room id
        $theRoomId = $room['room_id'] ?? $room;

        // get the self destruct time
        $selfDestruct  =  date('Y-m-d H:i:s', strtotime("+24 hours"));

        $this->getEncrypter($theRoomId);
        
        // encrypt the message
        $encryptData = $this->encrypter->encrypt($this->payload['message'] ?? '');

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

        // post the message
        $messageId = $this->chatsModel->postMessage($payload);

        // upload the media files if any
        if(!empty($this->payload['file_uploads'])) {
            $media = new Media();
            $media = $media->uploadMedia('chats', $messageId, $this->payload['sender'], $this->payload['file_uploads']);
        }

        return Routing::created(['data' => 'Message sent successfully', 'record' => [
            'roomId' => (int)$theRoomId,
            'userId' => (int)$this->payload['sender'],
            'messageId' => $messageId,
            'uuid' => $this->payload['uuid'] ?? $messageUUID,
            'media' => $media ?? []
        ]]);

    }

    /**
     * Get messages
     * 
     * @return array
     */
    public function messages() {

        // delete all group chats
        $senderId = $this->currentUser['user_id'] ?? 0;
        $receiverId = $this->payload['receiverId'] ?? 0;
        
        // create a new group chat
        if(!empty($this->payload['newGroupInfo']) && $this->payload['room'] == 'group') {
            $roomUUID = random_string('alnum', 32);

            // check if the group name is set
            if(empty($this->payload['newGroupInfo']['name'])) {
                return Routing::error('Group name is required');
            }

            // check if the group name is too long
            if(strlen($this->payload['newGroupInfo']['name']) > 60) {
                return Routing::error('Group name must be less than 60 characters');
            }

            // check if the group name is already taken
            $room = $this->chatsModel->getChatRoomByRoomName($this->payload['newGroupInfo']['name'], $senderId);
            if(!empty($room)) {
                return Routing::error('You already have a group with this name');
            }

            // create the chat room
            $roomId = $this->chatsModel->createChatRoom($senderId, 0, 'group', [(int)$senderId], $roomUUID, $this->payload['newGroupInfo']);

            // return the room id and room uuid
            return Routing::created(['data' => [], 'record' => [
                'roomId' => $roomId,
                'roomUUID' => $roomUUID,
            ]]);
        }

        // check if the room id or receiver id is set
        if(empty($this->payload['roomId']) && empty($this->payload['receiverId'])) {
            return Routing::error('Room ID or receiver ID is required');
        }
        
        if(!empty($this->payload['roomId'])) {
            $room  = $this->chatsModel->getChatRoom($this->payload['roomId']);
            if(empty($room)) {
                return Routing::success(['data' => [], 'record' => 'Chat room not found']);
            }
        }

        else {
            $room = $this->chatsModel->getIndividualChatRoomId($senderId, $receiverId);
            if(empty($room)) {
                return Routing::success(['data' => [], 'record' => 'Chat room not found']);
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
            if(!empty($message['self_destruct_at']) && time() > strtotime($message['self_destruct_at'])) continue;

            $append = false;

            // check if the message is from the sender
            if(($message['user_id'] == $senderId) && (int)$message['sender_deleted'] !== 1) {
                $append = true;
                $type = "sent";
            }
            elseif(($message['user_id'] == $receiverId) && (int)$message['receiver_deleted'] !== 1) {
                $append = true;
                $type = "received";
            } else {
                $append = true;
                $type = "received";
            }

            if($append) {
                
                if($message['content'] == 'notification::joined_chat') {
                    $imessage = "A user joined the chat";
                    $type = "joined";
                } else {
                    // decrypt the message
                    $imessage = !empty($message['content']) ? $this->encrypter->decrypt(base64_decode($message['content'])) : '';
                }

                $allowedMessages[] = [
                    'roomId' => $message['room_id'],
                    'msgid' => $message['message_id'],
                    'message' => linkifyChatJoin($imessage),
                    'sender' => $message['user_id'],
                    'media' => !empty($message['media']) ? json_decode($message['media'], true) : [],
                    'has_media' => !empty($message['media']),
                    'time' => date('h:i A', strtotime($message['created_at'])),
                    'uuid' => $message['unique_id'],
                    'created_at' => $message['created_at'],
                    'self_destruct_at' => $message['self_destruct_at'],
                    'type' => $type,
                ];
            }
        }

        return Routing::success(array_reverse($allowedMessages));

    }

    /**
     * Delete chat
     * 
     * @return array
     */
    public function delete() {
        if(empty($this->payload['roomId'])) {
            return Routing::error('Room ID is required');
        }

        if(empty($this->payload['type'])) {
            return Routing::error('Type is required');
        }

        // delete the chat
        $this->chatsModel->deleteChat($this->payload['roomId'], $this->payload['type'], $this->currentUser['user_id']);

        // delete the chat room
        return Routing::success('Chat deleted successfully');
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