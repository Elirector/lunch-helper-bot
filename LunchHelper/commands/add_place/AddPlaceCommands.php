<?php

    namespace Place\Commands;

    require_once('utils/Parser.php');

    use System\Parser;

    class AddPlaceCommands {
        public static function relocateWebhook($telegram, $properties) {
            $telegram->setWebhook(['url' => $properties["system"]["place_controller"]]);
        }

        public static function start($telegram, $chat_id, $keyboard) {
            $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Введите название места: ", 'reply_markup' => $reply_markup]);
        }

        public static function cancel($telegram, $chat_id, $properties) {
            $telegram->setWebhook(['url' => $properties["system"]["main_controller"]]);
            $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => [["Добавить место"], ["Предложи место"], ["Список заведений", "Помощь"]], 'resize_keyboard' => true, 'one_time_keyboard' => true]);
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Отменено", 'reply_markup' => $reply_markup]);
        }

        public static function setLocation($telegram, $chat_id, $text) {
            $arr = json_decode(file_get_contents("commands/add_place/place.json"), true);
            $arr["Location"] = $text;
            file_put_contents("commands/add_place/place.json", json_encode($arr), LOCK_EX);

            $reply = "Предпочтения к кальяну: ";
            $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => [["С Кальяном", "Без Кальяна"]], 'resize_keyboard' => true, 'one_time_keyboard' => true]);
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
        }

        public static function setHookah($telegram, $chat_id, $text) {
            $arr = json_decode(file_get_contents("commands/add_place/place.json"), true);
            $arr["Hookah Preference"] = $text;
            file_put_contents("commands/add_place/place.json", json_encode($arr));

            $reply = "Как обстоят дела с парковочными местами?";
            $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => [["С парковкой", "Без парковки"]], 'resize_keyboard' => true, 'one_time_keyboard' => true]);
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
        }

        public static function setParking($telegram, $chat_id, $text) {
            $arr = json_decode(file_get_contents("commands/add_place/place.json"), true);
            $arr["Parking"] = $text;
            file_put_contents("commands/add_place/place.json", json_encode($arr), LOCK_EX);

            $reply = "И наконец, тип заведения: ";
            $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => [["Бар", "Ресторан", "Фастфуд"], ["Рестобар", "Столовая", "Кафе"]], 'resize_keyboard' => true, 'one_time_keyboard' => true]);
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
        }

        public static function setType($telegram, $chat_id, $text, $properties) {
            $place_id = uniqid("place", true);

            $arr = json_decode(file_get_contents("commands/add_place/place.json"), true);
            $arr["Type"] = $text;

            $to_add = (file_exists("db.json")) ?  json_decode(file_get_contents("db.json"), true) : [];
            $to_add[$chat_id][$place_id] = $arr;

            file_put_contents("db.json", json_encode($to_add), LOCK_EX);

            $reply = "Сохранено";
            $keyboard = [["Добавить место"], ["Предложи место"], ["Список заведений", "Помощь"]];
            $telegram->setWebhook(['url' => $properties["system"]["main_controller"]]);
            $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
        }

        public static function setName($telegram, $chat_id, $text) {
            $arr = json_decode(file_get_contents("db.json"), true);
            if(Parser::parse($arr, $text)) {
                $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Такое место уже есть в базе: "]);
            }else {
                $arr = json_decode(file_get_contents("commands/add_place/place.json"), true);
                $arr["Place Name"] = $text;
                file_put_contents("commands/add_place/place.json", json_encode($arr), LOCK_EX);

                $reply = "Расположение вашего места: ";
                $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => [["Далеко", "Близко"]], 'resize_keyboard' => true, 'one_time_keyboard' => true]);
                $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
            }
        }
    }