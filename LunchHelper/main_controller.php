<?php
require_once('vendor/autoload.php'); //Подключаем библиотеку
require_once('utils/Properties.php');
require_once('commands/main/StartCommand.php');
require_once('commands/main/HelpCommand.php');
require_once('commands/main/ListPlacesCommand.php');
require_once('commands/add_place/AddPlaceCommands.php');
require_once('commands/choose_place/ChoosePlaceCommands.php');

use Telegram\Bot\Api;
use System\Properties;
use Main\Commands\StartCommand;
use Main\Commands\HelpCommand;
use Main\Commands\ListPlacesCommand;
use Place\Commands\AddPlaceCommands;
use Choice\Commands\ChoosePlaceCommands;

try {
    $properties = Properties::getProperties();

    $telegram = new Api($properties["system"]["token"]);//Устанавливаем токен, полученный у BotFather
    $result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя

    $text = $result["message"]["text"]; //Текст сообщения
    $chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
    $keyboard = [["Добавить место"], ["Предложи место"], ["Список заведений", "Помощь"]]; //Клавиатура

    switch ($text) {
        case "/start" :
        case "Начать" :
        case "Lunch Helper" :
            StartCommand::execute($telegram, $chat_id, $keyboard);
            break;

        case "/help" :
        case "Помощь" :
            HelpCommand::execute($telegram, $chat_id, $keyboard);
            break;

        case "Список заведений" :
            ListPlacesCommand::execute($telegram, $chat_id, $keyboard);
            break;

        case "Добавить место" :
            AddPlaceCommands::relocateWebhook($telegram, $properties);
            break;

        case "Предложи место" :
            ChoosePlaceCommands::relocateWebhook($telegram, $properties);
            break;
    }

} catch (\Telegram\Bot\Exceptions\TelegramSDKException $e) {
    echo $e->getMessage();
}