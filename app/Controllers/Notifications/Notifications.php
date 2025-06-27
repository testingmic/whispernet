<?php


namespace App\Controllers\Notifications;

use App\Controllers\LoadController;
use App\Libraries\Routing;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class Notifications extends LoadController {

    /**
     * Get the recent notifications for a user
     * 
     * @return array
     */
    public function recent() {
        
        // update the last activity time of the user
        $this->usersModel->update($this->currentUser['user_id'], ['last_login' => date('Y-m-d H:i:s')]);

        return Routing::success([]);

    }

    /**
     * Get the list of notifications for a user
     * 
     * @return array
     */
    public function list() {

        // connect to the notification database
        $this->notificationsModel->connectToDb('notification');
        
        // get the notifications
        $notifications = $this->notificationsModel->getUserNotifications($this->currentUser['user_id']);

        $theList = [];
        foreach($notifications as $notification) {
            $notification['time_ago'] = formatTimeAgo($notification['created_at']);
            $theList[] = $notification;
        }
        
        // format the notifications
        return Routing::success($theList);
    }

    /**
     * Delete a notification
     * 
     * @return array
     */
    public function delete() {
        
        // connect to the notification database
        $this->notificationsModel->connectToDb('notification');
        
        // delete the notification
        $this->notificationsModel->deleteRecord($this->payload['notification_id'], $this->currentUser['user_id']);
        
        return Routing::success('Notification successfully deleted.');
    }

    /**
     * Read a notification
     * 
     * @return array
     */
    public function read() {
        
        // connect to the notification database
        $this->notificationsModel->connectToDb('notification');
        
        // read the notification
        $this->notificationsModel->readRecord($this->payload['notification_id'], $this->currentUser['user_id']);
        
        return Routing::success('Notification successfully read.');
    }

    /**
     * Send a push notification to a user
     * 
     * @param array $data
     * @return array
     */
    public function push() {

        // prepare the data
        $preparedData = [
            'title' => $this->payload['title'] ?? 'TalkLowKey is buzzing!',
            'urlPath' => $this->payload['urlPath'] ?? configs('baseUrl'),
            'body' => $this->payload['body'] ?? 'Someone just posted anonymously 👀',
        ];

        // get the user id
        $userId = $this->payload['userId'];

        // get user settings
        $userSettings = formatUserSettings($this->usersModel->getUserSettings($userId), true);

        $getSub = array_filter($userSettings, function($setting) {
            return $setting['setting'] == 'sub_notification';
        });

        // if the user has no subscription, return an empty array
        if(empty($getSub)) {
            return Routing::success([]);
        }

        // get the value based on the first array key dynamically
        $getValue = array_values($getSub)[0]['value'];
        
        // create the subscription
        $subscription = Subscription::create([
            'endpoint' => $getValue['endpoint'],
            'publicKey' => $getValue['keys']['p256dh'],
            'authToken' => $getValue['keys']['auth'],
        ]);
        
        // create the auth
        $auth = [
            'VAPID' => [
                'subject' => $preparedData['title'],
                'publicKey' => configs('vapid_public'),
                'privateKey' => configs('vapid_private'),
            ],
        ];
        
        $webPush = new WebPush($auth);
        $webPush->sendOneNotification($subscription, json_encode([
            'title' => 'TalkLowKey is buzzing!',
            'urlPath' => '/install',
            'body' => 'Someone just posted anonymously 👀',
        ]));

        return Routing::success('Notification sent');

    }

    /**
     * Send a notification to all users
     * 
     * @return array
     */
    public function notifyall() {

        // get all users
        $users = $this->usersModel->getAllUsers('user_id, username, email, full_name, profile_image');

        // connect to the notification database
        $this->notificationsModel->connectToDb('notification');

        // check if the item is valid
        if(!in_array($this->payload['item'], ['system', 'features', 'updates'])) {
            return Routing::error('Invalid item submitted in the request.');
        }

        // send a notification to all users
        foreach($users as $user) {
            // notify the owner of the post or comment
            $this->notificationsModel->notify(
                $user['user_id'], $user['user_id'], $this->payload['item'], 'updates', $this->payload['message']
            );
        }

        return Routing::success('Notifications sent to all users');
    }
    
}