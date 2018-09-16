<?php

namespace Main\Commands;

require_once('Command.php');

class StartCommand implements Command {

    public static function execute($telegram, $chat_id, $keyboard)
    {
        $reply = "Здравствуйте, чем могу помочь?";
        $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
    }
}