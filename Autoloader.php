<?php
/**
 * Created by PhpStorm.
 * User: slebedeva
 * Date: 06.03.2019
 * Time: 14:34
 */

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}
Autoloader::register();