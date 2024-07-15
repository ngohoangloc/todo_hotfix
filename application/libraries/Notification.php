<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version4X;

class Notification
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function sendNotification($data)
    {
        try {

            $client = new Client(new Version4X('http://34.125.70.197:3000'));

            $client->connect();

            $client->emit('new_notification', [
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'message' => $data['message']
            ]);

            $client->disconnect();

        } catch (Exception $e) {
            log_message('error', 'Failed to send emit: ' . $e->getMessage());
        }
    }
}
