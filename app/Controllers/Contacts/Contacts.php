<?php

namespace App\Controllers\Contacts;

use App\Controllers\LoadController;
use App\Libraries\Routing;

class Contacts extends LoadController {
    
    /**
     * Send a contact message
     * 
     * @return void
     */
    public function send() {
        $this->contactsModel->create($this->payload);
        
        return Routing::success('Message sent successfully');
    }

    /**
     * Get all contacts
     * 
     * @return void
     */
    public function list() {
        $contacts = $this->contactsModel->getContacts();
        return Routing::success($contacts);
    }

}
