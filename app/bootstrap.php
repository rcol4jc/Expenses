<?php
require_once 'config/config.php';

//require_once 'libraries/Core.php';
//require_once 'libraries/Controller.php';
//require_once 'libraries/Database.php';

spl_autoload_register(function($className) {
    require_once 'libraries/' . $className . '.php';
});
require_once 'helpers/DateTimeHelper.php';
require_once 'helpers/Util.php';
require_once 'helpers/DatetimeFactory.php';