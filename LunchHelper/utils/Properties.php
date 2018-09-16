<?php

    namespace System;

    class Properties {

        public static function getProperties() {
            return parse_ini_file("settings.ini", true);
        }
    }