<?php
// core/classes/TelegramBot.php

class TelegramBot {
    private $token;
    private $chatId;

    public function __construct() {
        $config = require __DIR__ . '/../../config/telegram.php';
        $this->token = $config['bot_token'];
        $this->chatId = $config['chat_id'];
    }

    public function sendMessage($message, $keyboard = null) {
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";
        $data = [
            'chat_id' => $this->chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        if ($keyboard) {
            $data['reply_markup'] = json_encode($keyboard);
        }

        return $this->post($url, $data);
    }

    private function post($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}
