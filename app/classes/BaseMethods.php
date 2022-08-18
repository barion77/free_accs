<?php

use app\core\Config;

function abort($code)
{
    http_response_code($code);
    $config = require_once '../config/app.php';
    exit($code . ' ' . $config[0]['status_codes'][$code]);
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}
function logging($message)
{
    file_put_contents('../log/app.log', $message . "\r\n\r\n", FILE_APPEND);
}

function url($uri)
{
    $app_url = Config::getField('APP_URL');
    return trim($app_url, '/') . $uri;
}
