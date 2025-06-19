<?php

namespace App\Controllers\Chats;

use Exception;
use App\Controllers\LoadController;
use App\Libraries\Routing;

class Chats extends LoadController {

    public $selfDestruct = 24;

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
     * Send message
     * 
     * @return array
     */
    public function sendMessage() {

        // check if the sender is the current user
        if((int)$this->payload['sender'] !== (int)$this->currentUser['user_id']) {
            return Routing::error('You are not the sender of this message');
        }

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
                    $this->payload['sender'],
                    $this->payload['receiver']
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

        $selfDestruct  = time() + ($this->selfDestruct * 60);
        
        // encrypt the response
        $encryptData = $this->encryptions->encrypt(json_encode($this->payload['message']));

        $payload = [
            'room_id' => $theRoomId,
            'is_encrypted' => 1,
            'user_id' => $this->payload['sender'],
            'content' => base64_encode($encryptData),
            'self_destruct_at' => $selfDestruct
        ];

        return Routing::created(['data' => 'Message sent successfully', 'record' => [
            'roomId' => $theRoomId,
            'messageId' => $this->chatsModel->postMessage($payload)
        ]]);

    }

    /**
     * Get messages
     * 
     * @return array
     */
    public function getMessages() {
        return $this->chatsModel->getMessages($this->payload['roomId'], $this->payload['userId'], $this->payload['page'], $this->payload['limit']);
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