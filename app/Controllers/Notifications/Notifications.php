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
    
}