<?php


// require config file :
require_once 'config/config.php';
// require core file :
//require_once 'core/Controller.php';
//require_once 'core/Core.php';
//require_once 'core/Database.php';

// you can do that or namespace:
spl_autoload_register(function ($className) {
    require_once 'core/' . $className . '.php';
});