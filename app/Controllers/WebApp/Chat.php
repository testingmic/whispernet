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
        // get the user chat rooms
        $chatRooms = (new chatsModel())->getUserChatRooms($this->session->get('user_id'));

        // group the chats by room id
        $groupChats = [];
        $footerArray = [];
        foreach($chatRooms as $key => $chat) {
            // $chat['last_login'] = convertTimestampToDate($chat['last_login']);
            $chat['state'] = userState($chat['last_login']);
            // $chat['date_range'] = timeDifference($chat['last_login']);
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