<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class ContactsModel extends Model {

    protected $table = 'contacts';
    protected $primaryKey = 'contact_id';
    protected $allowedFields = ['name', 'email', 'message', 'subject', 'user_id', 'token'];

    /**
     * Create a contact record
     * 
     * @param array $payload
     * 
     * @return bool
     */
    public function create($payload) {
        try {
            $this->insert($payload);
            return true;
        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Get a contact record
     * 
     * @param int $contactId
     * 
     * @return array
     */
    public function getContact($contactId) {
        try {
            return $this->find($contactId);
        } catch (DatabaseException $e) {
            return null;
        }
    }

    /**
     * Get all contacts
     * 
     * @return array
     */
    public function getContacts() {
        try {
            return $this->findAll();
        } catch (DatabaseException $e) {
            return null;
        }
    }

    /**
     * Get a contact by token
     * 
     * @param string $token
     * 
     * @return array
     */
    public function getContactByToken($token) {
        try {
            return $this->where('token', $token)->findAll();
        } catch (DatabaseException $e) {
            return null;
        }
    }

    /**
     * Get contacts by email
     * 
     * @param string $email
     * 
     * @return array
     */
    public function getContactsByEmail($email) {
        try {
            return $this->where('email', $email)->findAll();
        } catch (DatabaseException $e) {
            return null;
        }
    }

    /**
     * Get contacts by subject
     * 
     * @param string $subject
     * 
     * @return array
     */
    public function getContactsBySubject($subject) {
        try {
            return $this->where('subject', $subject)->findAll();
        } catch (DatabaseException $e) {
            return null;
        }
    }

    /**
     * Get contacts by user id
     * 
     * @param int $userId
     * 
     * @return array
     */
    public function getContactsByUserId($userId) {
        try {
            return $this->where('user_id', $userId)->findAll();
        } catch (DatabaseException $e) {
            return null;
        }
    }

    /**
     * Delete a contact record
     * 
     * @param int $contactId
     * 
     * @return bool
     */
    public function deleteRecord($contactId) {
        try {
            return $this->delete($contactId);
        } catch (DatabaseException $e) {
            return false;
        }
    }

    /**
     * Update a contact record
     * 
     * @param int $contactId
     * @param array $payload
     * 
     * @return bool
     */
    public function updateRecord($contactId, $payload) {
        try {
            return $this->update($contactId, $payload);
        } catch (DatabaseException $e) {
            return false;
        }
    }
    
}