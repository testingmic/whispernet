<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;
use App\Models\ChatsModel;

class Chat extends WebAppController {
    
    /**
     * Index
     * 
     * @return array
     */
    public function index() {

        // verify if the user is logged in
        $this->verifyLogin();
        
        // get the user chat rooms
        $chatRooms = (new chatsModel())->getUserChatRooms($this->session->get('user_id'));

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

        // return the list
        return $this->templateObject->loadPage('chat', [
            'pageTitle' => 'Chat', 
            'chatRooms' => $chatRooms, 
            'groupChats' => $groupChats, 
            'favicon_color' => 'chat', 
            'footerArray' => $footerArray,
            'footerHidden' => true,
            'chatSection' => true
        ]);
    }

}