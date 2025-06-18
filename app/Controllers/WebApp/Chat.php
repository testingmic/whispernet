<?php

namespace App\Controllers\WebApp;

use App\Controllers\WebAppController;

class Chat extends WebAppController {
    
    public function index() {
        $chats = [[], [], [], [], [], []];
        return $this->templateObject->loadPage('chat', ['pageTitle' => 'Chat', 'chats' => $chats]);
    }

    /**
     * Groups page
     * 
     * @return string
     */
    public function groups() {
        $groups = [];
        return $this->templateObject->loadPage('groups', ['pageTitle' => 'Groups', 'groups' => $groups]);
    }

    /**
     * Group page
     * 
     * @param string $groupId
     * @param array $params
     * @return string
     */
    public function group($groupId = null, $params = []) {
        $group = [];
        return $this->templateObject->loadPage('group_chat', ['pageTitle' => 'Group', 'group' => $group]);
    }

}