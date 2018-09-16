<?php
namespace System;

class Parser {
    static function parse($array, $item) {
        $result = false;

        //Chat ID
        foreach ($array as $key=>$value) {
            //Place ID
            foreach ($value as $key2=>$value2) {
                //Flags
                foreach ($value2 as $key3=>$value3) {
                    if($value3 === $item) {
                        $result = true;
                    }
                }
            }
        }

        return $result;
    }
}