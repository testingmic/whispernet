<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Libraries\Caching;
use App\Libraries\Encryptions;
use App\Models\DBModel;

use App\Models\PostsModel;
use App\Models\AnalyticsModel;
use App\Models\TagsModel;
use App\Models\ChatsModel;

class LoadController extends BaseController
{
 
    protected $dbModel;
    protected $usersModel;
    protected $accessModel;
    protected $authModel;
    protected $encryptions;
    
    protected $postsModel;
    protected $analyticsModel;
    protected $tagsModel;
    protected $chatsModel;

    public function __construct($payload = [])
    {
        // initialize the models
        $this->authModel = new AuthModel();
        $this->dbModel = new DBModel();

        if(empty($this->payload)) {
            $this->payload = $payload;
        }
        
        // initialize the cache object
        if(empty($this->cacheObject)) {
            $this->cacheObject = new Caching();
        }

        $this->encryptions = new Encryptions();

        // get the last name of the class that has been called and trigger the model
        $childClass = get_called_class();
        $getLastName = explode('\\', $childClass);
        $triggeredModel = $getLastName[count($getLastName) - 1];

        $this->triggerModel(strtolower($triggeredModel));
    }

    /**
     * Trigger model
     * 
     * @param array $model
     * @return void
     */
    public function triggerModel($model) {
        
        $models = stringToArray($model);
        
        // Define a mapping of model names to their corresponding model classes
        $modelMap = [
            'posts' => PostsModel::class,
            'analytics' => AnalyticsModel::class,
            'tags' => TagsModel::class,
            'chats' => ChatsModel::class,
        ];
        
        // Loop through the requested models and initialize them
        foreach ($models as $modelName) {
            if (isset($modelMap[$modelName])) {
                $propertyName = $modelName . 'Model';
                $this->{$propertyName} = !empty($this->{$propertyName}) ? $this->{$propertyName} : new $modelMap[$modelName]();
                $this->{$propertyName}->payload = $this->payload;
            }
        }
    }

    /**
     * Handle error
     * 
     * @param \Exception $e
     * @return array
     */
    protected function handleError($e) {
        // Log error
        error_log($e->getMessage());
        
        // Return error response
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }

    /**
     * Success
     * 
     * @param mixed $data
     * @return array
     */
    protected function success($data = null) {
        return [
            'success' => true,
            'data' => $data
        ];
    }

    /**
     * Validate required
     * 
     * @param array $data
     * @param array $fields
     * @return void
     */
    protected function validateRequired($data, $fields) {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
    }

}
