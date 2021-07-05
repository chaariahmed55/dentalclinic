<?php

namespace App\Utils;

class Utils
{
    public static function jresponce(string $status, string $message, string $data): string
    {
        return '{ 
            "STATUS" : "'.$status.'", 
            "MESSAGE": "'.preg_replace('/[^a-zA-Z0-9_.]/', '*', $message).'",
            "DATA": '.$data.'
        }';
    }
}

?>