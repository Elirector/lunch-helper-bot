<?php
require_once('vendor/autoload.php');
require_once('utils/Properties.php');
require_once('commands/choose_place/ChoosePlaceCommands.php');

use Telegram\Bot\Api;
use System\Properties;
use Choice\Commands\ChoosePlaceCommands;

try {
    $properties = Properties::getProperties();

    $telegram = new Api($properties["system"]["token"]);

    $result = $telegram->getWebhookUpdates();
    
    $text = $result["message"]["text"];
    $chat_id = $result["message"]["chat"]["id"];
    $keyboard = [["С кальяном", "Без кальяна"], ["Далеко", "Близко"], ["С парковкой", "Без парковки"], ["Бар", "Ресторан", "Фастфуд"], ["Рестобар", "Столовая", "Кафе"],["Нет"]];
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);

    switch ($text) {
        case "Предложи место" :
            ChoosePlaceCommands::start($telegram, $chat_id, $reply_markup);
            break;

        case "Нет" :
            ChoosePlaceCommands::makeAChoice($telegram, $chat_id, $properties);
            break;

        default :
            ChoosePlaceCommands::keepAsking($telegram, $chat_id, $telegram, $reply_markup);
            break;
    }

} catch (\Telegram\Bot\Exceptions\TelegramSDKException $e) {
    echo $e->getMessage();
}