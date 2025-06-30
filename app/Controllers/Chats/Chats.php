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
            if($room['room']['type'] == 'group' && empty($room['room_uuid'])) continue;
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
     * Join a chat room
     * 
     * @return array
     */
    public function join($payload = []) {

        // get the room id
        $roomId = $payload['roomId'] ?? $this->payload['roomId'];

        // get the chat room
        $chatRoom = $this->chatsModel->getChatRoom($roomId);
        if(empty($chatRoom)) {
            return false;
        }

        // get the room uuid
        $roomUUID = $payload['roomUUID'] ?? $this->payload['roomUUID'];

        // check if the room uuid is valid
        if(($chatRoom['room_uuid'] !== $roomUUID) || ($chatRoom['type'] !== 'group')) {
            return false;
        }

        // receipients list
        $receipientsList = json_decode($chatRoom['receipients_list'], true);

        // get the user id
        $userId = $payload['userId'] ?? $this->currentUser['user_id'];

        if(!in_array($userId, $receipientsList)) {
            $receipientsList[] = $userId;
            $this->chatsModel->joinChatRoom($roomId, $userId, ['receipients_list' => json_encode($receipientsList)]);

            // post a notification to the chat room
            $payload = [
                'room_id' => $roomId,
                'is_encrypted' => 1,
                'unique_id' => generateUUID(),
                'user_id' => $userId,
                'content' => 'notification::joined_chat',
                'self_destruct_at' => date('Y-m-d H:i:s', strtotime("+24 hours"))
            ];
    
            // post the message
            $this->chatsModel->postMessage($payload);
        }

        // return the room id
        return $payload['returnBoolean'] ?? Routing::success(['data' => 'Joined the chat room', 'record' => $this->rooms()['data']]);

    }

    /**
     * Leave a chat room
     * 
     * @return array
     */
    public function leave() {

        // check if the room id is set
        if(empty($this->payload['roomId'])) {
            return Routing::error('Chat Room ID is required');
        }
        
        // get the room ids
        $roomId = $this->payload['roomId'];

        // get the chat room
        $chatRoom = $this->chatsModel->getChatRoom($roomId);
        if(empty($chatRoom)) {
            return Routing::error('Chat room not found');
        }

        // receipients list
        $receipientsList = json_decode($chatRoom['receipients_list'], true);

        // get the user id
        $userId = $payload['removeUserId'] ?? $this->currentUser['user_id'];

        if(!in_array($userId, $receipientsList)) {
            return Routing::success('You are not a participant of this chat');
        }

        // remove the user from the receipients list
        $receipientsList = array_diff($receipientsList, [$userId]);

        // update the receipients list
        $this->chatsModel->leaveChatRoom($roomId, $userId, $receipientsList);

        // post a notification to the chat room
        $payload = [
            'room_id' => $roomId,
            'is_encrypted' => 1,
            'unique_id' => generateUUID(),
            'user_id' => $userId,
            'content' => 'notification::left_chat',
            'self_destruct_at' => date('Y-m-d H:i:s', strtotime("+24 hours"))
        ];

        // post the message
        $this->chatsModel->postMessage($payload);
        
        // return the success message
        return Routing::success(['data' => 'Left the chat room', 'record' => $this->rooms()['data']]);
    }

    /**
     * Create a new group
     * 
     * @return array
     */
    public function creategroup() {

        // set the group name and description
        $this->payload['newGroupInfo']['name'] = $this->payload['name'];
        $this->payload['newGroupInfo']['description'] = $this->payload['description'];
        $this->payload['room'] = 'group';

        // get the messages
        return $this->messages();
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
                $room['receipients_list'] = json_decode($room['receipients_list'], true);
                return Routing::created(['data' => 'You already have a group with this name', 'record' => [
                    'roomId' => $room['room_id'],
                    'roomUUID' => $room['room_uuid'],
                    'roomData' => $room
                ]]);
            }

            // create the chat room
            $roomId = $this->chatsModel->createChatRoom($senderId, 0, 'group', [(int)$senderId], $roomUUID, $this->payload['newGroupInfo']);

            // return the room id and room uuid
            return Routing::created(['data' => [], 'record' => [
                'roomId' => $roomId,
                'roomUUID' => $roomUUID,
                'roomData' => [
                    'type' => 'group',
                    'room_id' => $roomId,
                    'room_uuid' => $roomUUID,
                    'creator' => $senderId,
                    'room' => [
                        'participants' => [
                            $senderId
                        ]
                    ],
                    'full_name' => explode(' ', $this->payload['newGroupInfo']['name'])[0],
                    'username' => $this->payload['newGroupInfo']['name'],
                    'user_id' => $senderId,
                    'name' => $this->payload['newGroupInfo']['name'],
                    'participants' => '1 participant'
                ]
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
            return Routing::success(['data' => [], 'record' => 'You are not a participant of this chat']);
        }

        // get the encrypter object
        $this->getEncrypter($room['room_id']);

        // get the messages
        $messages = $this->chatsModel->getMessages($room['room_id'], $this->payload['offset'], $this->payload['limit']);
        
        $allowedMessages = [];

        $roomId = 0;
        $roomUUID = '';

        $roomId = $room['room_id'] ?? 0;
        $roomUUID = $room['room_uuid'] ?? '';

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
            }
            elseif($this->payload['room'] == 'group') {
                $append = true;
                $type = "received";
            }

            if($append) {

                // default content
                $defaultContent = [
                    'notification::joined_chat' => "A user joined the conversation",
                    'notification::left_chat' => "A user left the conversation",
                    'notification::kicked_out' => "A user was kicked out of the conversation",
                ];
                
                // check if the message is a default content
                if(isset($defaultContent[$message['content']])) {
                    $imessage = $defaultContent[$message['content']];
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

        return Routing::success(array_reverse($allowedMessages), ['roomId' => $roomId, 'roomUUID' => $roomUUID]);

    }

    /**
     * Remove a chat from the list
     * 
     * @return array
     */
    public function remove() {
        if(empty($this->payload['roomId'])) {
            return Routing::error('Chat Room ID is required');
        }

        // get the chat room
        $room = $this->chatsModel->getChatRoomByRoomId($this->payload['roomId'], $this->currentUser['user_id']);
        if(empty($room)) {
            return Routing::error('Chat room not found');
        }

        $roomId = (int)$this->payload['roomId'];
        $room  = $this->chatsModel->getChatRoom($roomId);
        if(empty($room)) {
            return Routing::error('Chat room not found');
        }

        // get the receipients list
        $receipientsList = json_decode($room['receipients_list'], true);

        // get the user id
        $userId = (int)$this->currentUser['user_id'];
        
        // check if the sender is a participant of the chat
        if(!in_array($userId, $receipientsList)) {
            return Routing::error('You are not a participant of this chat');
        }

        // delete the chat
        $this->chatsModel->deleteChat($roomId, $userId);

        // remove the user from the receipients list
        $receipientsList = array_diff($receipientsList, [$userId]);

        // update the sender deleted status
        if($room['sender_id'] == $userId) {
            $this->chatsModel->updateChatRoom($roomId, ['sender_deleted' => 1]);
        }

        // update the receiver deleted status
        if($room['receiver_id'] == $userId) {
            $this->chatsModel->updateChatRoom($roomId, ['receiver_deleted' => 1]);
        }

        // delete the user from the chat room
        $this->chatsModel->chatsDb->table('user_chat_rooms')->where('room_id', $roomId)->where('user_id', $userId)->delete();

        // return the success message
        return Routing::success('Chat removed successfully');
    }

    /**
     * Delete chat
     * 
     * @return array
     */
    public function delete() {
        if(empty($this->payload['roomId'])) {
            return Routing::error('Chat Room ID is required');
        }

        if(empty($this->payload['type'])) {
            return Routing::error('Type is required');
        }

        // delete the chat
        $this->chatsModel->deleteChat($this->payload['roomId'], $this->currentUser['user_id']);

        // if the action is remove, return the rooms
        if(!empty($this->payload['action']) && $this->payload['action'] == 'remove') {
            $this->remove();
        }

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