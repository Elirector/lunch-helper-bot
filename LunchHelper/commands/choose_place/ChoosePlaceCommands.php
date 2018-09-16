<?php

namespace Choice\Commands;

require_once("utils/Sorter.php");

use System\Sorter;

class ChoosePlaceCommands
{

    public static function relocateWebhook($telegram, $properties) {
        $telegram->setWebhook(['url' => $properties["system"]["choice_controller"]]);
    }

    public static function start($telegram, $chat_id, $reply_markup) {
        $reply = "Есть ли у вас пожелания по поиску места?";
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
    }

    public static function makeAChoice($telegram, $chat_id, $properties)
    {
        $keyboard = [["Добавить место"], ["Предложи место"], ["Список заведений", "Помощь"]];
        $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);

        $flags = json_decode(file_get_contents("commands/choose_place/flags.json"), true);
        $arr = json_decode(file_get_contents("db.json"), true);

        $places = [];

        if ($flags === null) {
            foreach($arr[$chat_id] as $place) {
                $places[] = $place;
            }
        }else{
            $flags = Sorter::sorter($flags);
            $places = $arr[$chat_id];

            foreach ($flags as $key=>$flag) {
                $tmp = [];
                foreach ($places as $place) {
                    foreach ($place as $key2=>$pl) {
                        if($key === $key2 && $flag === $pl) {
                            $tmp[] = $place;
                        }
                    }
                }
                $places = $tmp;
            }
        }

        $response = "И так! Место, куда вам стоит пойти : \n" . $places[rand(0, count($places)-1)]["Place Name"];

        $telegram->setWebhook(['url' => $properties["system"]["main_controller"]]);
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $response, 'reply_markup' => $reply_markup]);

        fclose(fopen("commands/choose_place/flags.json", "w+t"));
    }

    public static function keepAsking($telegram, $chat_id, $text, $reply_markup) {
        $arr = json_decode(file_get_contents("commands/choose_place/flags.json"), true);
        $arr["Flags"][] = $text;
        file_put_contents("commands/choose_place/flags.json" ,json_encode($arr), LOCK_EX);

        $reply = "Хотите указать ещё флаг?";
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
    }
}