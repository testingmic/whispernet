<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;
use App\Models\ChatsModel;

class Chat extends WebAppController {
    
    private $chatsModel;

    public function chatData() {

        // get the chats model
        $this->chatsModel = !empty($this->chatsModel) ? $this->chatsModel : new ChatsModel();
        
        // get the user chat rooms
        $chatRooms = $this->chatsModel->getUserChatRooms($this->session->get('user_id'));

        // group the chats by room id
        $groupChats = [];
        $footerArray = [];
        foreach($chatRooms as $key => $chat) {
            $chat['state'] = userState($chat['last_login']);
            if(!empty($chat['full_name'])) {
                $chat['full_name'] = explode(' ', $chat['full_name'])[0];
            }
            $footerArray[$chat['room_id']] = $chat;
            if($chat['room']['type'] === 'group') {
                $groupChats[] = $chat;
            }
        }

        return [
            'chatRooms' => $chatRooms, 
            'groupChats' => $groupChats, 
            'footerArray' => $footerArray
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

        // get the chat room
        $chatRoom = $this->chatsModel->getChatRoom($roomId);
        if(empty($chatRoom)) {
            return $this->templateObject->load404Page();
        }

        // check if the room uuid is valid
        if($chatRoom['room_uuid'] !== $roomUUID && $chatRoom['type'] !== 'group') {
            return $this->templateObject->load404Page();
        }

        // receipients list
        $receipientsList = json_decode($chatRoom['receipients_list'], true);

        $userId = $this->session->get('user_id');

        if(!in_array($userId, $receipientsList)) {
            $receipientsList[] = $userId;
            $this->chatsModel->joinChatRoom($roomId, $userId, ['receipients_list' => json_encode($receipientsList)]);
        }

        // get the chat data
        $chatData = $this->chatData();

        // get the chat room
        return $this->templateObject->loadPage('chat', [
            'pageTitle' => 'Chat Join', 
            'favicon_color' => 'chat', 
            'chatRooms' => $chatData['chatRooms'], 
            'groupChats' => $chatData['groupChats'], 
            'footerArray' => $chatData['footerArray'],
            'footerHidden' => true,
            'chatSection' => true
        ]);
    }

}