<?php 

namespace app\core;

trait Singleton 
{
    private static $_instance;

    static public function getInstance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }

        return self::$_instance = new self;
    }
}