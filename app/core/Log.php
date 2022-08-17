<?php 

namespace app\core;

class Log 
{
    public static function write($message)
    {
        file_put_contents('../log/app.log', $message . "\r\n\r\n");
    }
}