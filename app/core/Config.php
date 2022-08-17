<?php 

namespace app\core;

class Config 
{
    public static function getField(string $field)
    {
        $ini = parse_ini_file('../app.ini');

        return $ini[$field];
    }

    public static function getSection(string $section)
    {
        $ini = parse_ini_file('../app.ini', true);

        return $ini[$section];
    }
}