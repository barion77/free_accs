<?php

use app\core\Config;
use app\core\Route;
use app\exceptions\RouteException;

session_start();

include '../app/classes/dev.php';

require '../app/classes/BaseMethods.php';
require '../app/classes/autoload.php';
require '../routes/web.php';

try {
    
} catch (RouteException $e) {
    if (Config::getField('APP_LOG')) {
        logging('Class or method does not exists ' . $controller . ' method: ' . $action);
    }
    exit($e->getMessage());
}

Route::check();
