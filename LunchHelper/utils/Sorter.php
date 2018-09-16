<?php

namespace System;

class Sorter {
    public static function sorter($arr) {
            $array = [];

            foreach ($arr as $key=>$value) {
                foreach ($value as $val) {
                    store($array, $val);
                }
            }

            return $array;

        function store(&$array, $text) {
            switch ($text) {
                case "Далеко":
                case "Близко":
                    $array["Location"] = $text;
                    break;

                case "С парковкой":
                case "Без парковки":
                    $array["Parking"] = $text;
                    break;

                case "С Кальяном":
                case "Без Кальяна":
                    $array["Hookah Preference"] = $text;
                    break;

                default:
                    $array["Type"] = $text;
                    break;
            }
        }
    }
}