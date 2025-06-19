<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class MediaModel extends Model
{
    protected $table = 'media';
    protected $primaryKey = 'media_id';
    protected $allowedFields = ['section', 'record_id', 'user_id', 'media', 'created_at', 'updated_at'];

    public function __construct() {
        parent::__construct();
        
        foreach(DbTables::initTables() as $key) {
            if (property_exists($this, $key)) {
                $this->{$key} = DbTables::${$key};
            }
        }
    }

    /**
     * Get a media record
     * @param int $mediaId
     * 
     * @return array
     */
    public function getMediaRecord($mediaId) {
        return $this->find($mediaId);
    }

    /**
     * Get a media record by record id
     * @param string $recordId
     * 
     * @return array
     */
    public function getMediaRecordByRecordId($recordId) {
        return $this->where('record_id', $recordId)->findAll();
    }

    /**
     * Get a media record by record id and section
     * @param string $recordId
     * @param string $section
     * 
     * @return array
     */
    public function getMediaRecordByRecordIdAndSection($recordId, $section) {
        return $this->where('record_id', $recordId)->where('section', $section)->findAll();
    }

    /**
     * Delete a media record
     * @param int $mediaId
     * 
     * @return array
     */
    public function deleteMediaRecord($mediaId) {
        return $this->delete($mediaId);
    }

    /**
     * Create a media record
     * @param array $uploadedList
     * @param string $section
     * @param int $recordId
     * @param int $userId
     * 
     * @return array
     */
    public function createMediaRecord($uploadedList, $section, $recordId, $userId) {

        try {

            $data = [
                'section' => $section,
                'record_id' => $recordId,
                'user_id' => $userId,
                'media' => json_encode($uploadedList),
            ];
            $this->insert($data);

            return $this->getInsertID();
        } catch (DatabaseException $e) {
            print_r($e->getMessage());exit;
            return $e->getMessage();
        }
    }
}