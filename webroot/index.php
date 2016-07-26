<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('VIEWS_PATH', ROOT.DS.'views');
define('UPLOADS', ROOT.DS.'webroot'.DS.'uploads');
define('COUNT_NEWS', 5);
define('USER_COUNTS', 5);

require_once(ROOT.DS.'lib'.DS.'init.php');

session_start();

App::run($_SERVER['REQUEST_URI']);