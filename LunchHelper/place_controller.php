<?php
require_once('vendor/autoload.php');
require_once('utils/Properties.php');
require_once('commands/add_place/AddPlaceCommands.php');

use Telegram\Bot\Api;
use System\Properties;
use Place\Commands\AddPlaceCommands;

try{
    $properties = Properties::getProperties();

    $telegram = new Api($properties["system"]["token"]);
    $result = $telegram -> getWebhookUpdates();

    $text = $result["message"]["text"];
    $chat_id = $result["message"]["chat"]["id"];
    $keyboard = [["Отмена"]];

    switch ($text) {
        case "Добавить место" :
            AddPlaceCommands::start($telegram, $chat_id, $keyboard);
            break;

        case "Отмена" :
            AddPlaceCommands::cancel($telegram, $chat_id, $properties);
            break;

        case "Далеко" :
        case "Близко" :
            AddPlaceCommands::setLocation($telegram, $chat_id, $text);
            break;

        case "С Кальяном" :
        case "Без Кальяна" :
            AddPlaceCommands::setHookah($telegram, $chat_id, $text);
            break;

        case "С парковкой" :
        case "Без парковки" :
            AddPlaceCommands::setParking($telegram, $chat_id, $text);
            break;

        case "Бар"      :
        case "Ресторан" :
        case "Фастфуд"  :
        case "Рестобар" :
        case "Столовая" :
        case "Кафе"     :
            AddPlaceCommands::setType($telegram, $chat_id, $text, $properties);
            break;

        default :
            AddPlaceCommands::setName($telegram, $chat_id, $text);
            break;
    }
} catch (\Telegram\Bot\Exceptions\TelegramSDKException $e) {
    echo $e->getMessage();
}