<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;
use App\Models\ChatsModel;
use App\Controllers\Chats\Chats;

class Chat extends WebAppController {
    
    private $chatsModel;

    /**
     * Load the chat data
     * 
     * @return array
     */
    public function chatData() {

        // get the chats model
        $this->chatsModel = !empty($this->chatsModel) ? $this->chatsModel : new ChatsModel();
        
        // get the user chat rooms
        $chatRooms = $this->chatsModel->getUserChatRooms($this->loogedUserId);

        // group the chats by room id
        $groupChats = [];
        $individualChats = [];
        $footerArray = [];
        foreach($chatRooms as $key => $chat) {
            $chat['state'] = userState($chat['last_login']);
            if(!empty($chat['full_name'])) {
                $chat['full_name'] = explode(' ', $chat['full_name'])[0];
            }
            $footerArray[$chat['room_id']] = $chat;
            if($chat['room']['type'] === 'group') {
                $groupChats[] = $chat;
            } else if($chat['room']['type'] === 'individual') {
                $individualChats[] = $chat;
            }
        }

        return [
            'chatRooms' => $chatRooms, 
            'groupChats' => $groupChats, 
            'footerArray' => $footerArray,
            'individualChats' => $individualChats
        ];
    }

    /**
     * Index
     * 
     * @return array
     */
    public function index() {

        // verify if the user is logged in
        $this->verifyLogin();
        
        // get the chat data
        $chatData = $this->chatData();

        // return the list
        return $this->templateObject->loadPage('chat', [
            'pageTitle' => 'Chat', 
            'chatRooms' => $chatData['chatRooms'], 
            'groupChats' => $chatData['groupChats'], 
            'favicon_color' => 'chat', 
            'individualChats' => $chatData['individualChats'],
            'footerArray' => $chatData['footerArray'],
            'footerHidden' => true,
            'chatSection' => true
        ]);
    }

    /**
     * Join chat
     * 
     * @param int $roomId
     * @param string $roomUUID
     * @param string $roomType
     * @return array
     */
    public function join($roomId = '', $roomUUID = '') {

        // verify if the user is logged in
        $this->verifyLogin();

        // get the chats model
        $this->chatsModel = new ChatsModel();

        // check if the room id is a number
        if(empty($roomId) || !is_numeric($roomId) || empty($roomUUID)) {
            return $this->templateObject->load404Page();
        }

        // join the chat room
        $join = (new Chats())->join([
            'returnBoolean' => true,
            'roomId' => $roomId,
            'roomUUID' => $roomUUID,
            'userId' => $this->loogedUserId,
        ]);

        // check if the join was successful
        if(!$join) {
            return $this->templateObject->load404Page();
        }

        // get the chat data
        $chatData = $this->chatData();

        // get the chat room
        return $this->templateObject->loadPage('chat', [
            'pageTitle' => 'Chat Join', 
            'favicon_color' => 'chat', 
            'selectedRoom' => $roomId,
            'selectedRoomUUID' => $roomUUID,
            'individualChats' => $chatData['individualChats'],
            'chatRooms' => $chatData['chatRooms'], 
            'groupChats' => $chatData['groupChats'], 
            'footerArray' => $chatData['footerArray'],
            'footerHidden' => true,
            'chatSection' => true
        ]);
    }

}