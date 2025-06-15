<?php

namespace App\Controllers\Chats;

use Exception;
use App\Controllers\LoadController;
use App\Libraries\Routing;

class Chats extends LoadController {

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
        return $this->chatsModel->sendMessage($this->payload['roomId'], $this->payload['userId'], $this->payload['content'], $this->payload['mediaUrl'], $this->payload['mediaType']);
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