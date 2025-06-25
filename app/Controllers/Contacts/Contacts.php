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

        // If token is not provided, use fingerprint
        $this->payload['token'] = empty($this->payload['token']) ? $this->payload['fingerprint'] : $this->payload['token'];

        // get the list of contacts sent out by the user
        $contacts = $this->contactsModel->getContactByToken($this->payload['token']);
        if(count($contacts) > 10) {
            return Routing::error('You have reached the maximum number of contacts you can send');
        }

        // Create contact record
        $this->contactsModel->create($this->payload);
        
        // Return success response
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
