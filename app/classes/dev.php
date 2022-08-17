<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {

} catch (\Exception $e) {
    throw new Exception($e->getMessage());
}

function debug() 
{
    $args = func_get_args();
    echo '<div style="margin: 1em 0.5em; color: #333;font-size:16px;"><fieldset style="background-color: #CAE6D7;display:inline-block;margin: 0 0.5em;"><legend><strong>Parametrs: ' . count($args) . '</strong></legend><pre>';
    var_dump($args);
    echo '</fieldset></pre></div>';
    exit;
}