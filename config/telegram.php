<?php
// config/telegram.php

return [
    'bot_token' => '7704406393:AAF27v7soy5S-hlnWrRTiURCT8Bk_lhALjE', // Updated with real token
    'chat_id' => '7372079283', // Updated with real admin chat id
    'callback_url' => getenv('TELEGRAM_CALLBACK_URL') ?: 'https://mekongcyberunit.app/public/api/telegram_callback.php'
];
