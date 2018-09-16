<?php

namespace Main\Commands;

require_once("Command.php");

class HelpCommand implements Command {

    static function execute($telegram, $chat_id, $keyboard)
    {
        $reply = "Доступные комманды:
                  /help - доступные комманды
                  /start - перезапустить бота";
        $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
    }
}