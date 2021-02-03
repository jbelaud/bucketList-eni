<?php

namespace App\Util;

class Censurator
{
    public function purify(String $text): string
    {

        $badWords = ["banane", "poire", "raisin", "pêche", "épinard"];

        foreach ($badWords as $badWord){
            $replacement = str_repeat("*", mb_strlen($badWord));
            $text =str_ireplace($badWord, $replacement, $text);
        }

        return $text;
    }
}