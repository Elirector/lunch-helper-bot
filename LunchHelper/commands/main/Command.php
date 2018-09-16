<?php

    namespace Main\Commands;

    interface Command {
        static function execute($telegram, $chat_id, $keyboard);
    }