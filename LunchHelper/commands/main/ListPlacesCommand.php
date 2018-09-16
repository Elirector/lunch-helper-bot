<?php

    namespace Main\Commands;

    require_once('Command.php');

    class ListPlacesCommand implements Command {

        static function execute($telegram, $chat_id, $keyboard)
        {
            $str = "";

            $arr = json_decode(file_get_contents("db.json"), true);

            if(array_key_exists($chat_id, $arr)) {
                echo "Список заведений \n";
                foreach ($arr[$chat_id] as $value) {
                    $str .= "* " . $value["Place Name" ] . "\n";
                }
            }else {
                $str = "В вашем списке ещё нет заведений!";
            }

            $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);

            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $str, 'reply_markup' => $reply_markup]);
        }
    }