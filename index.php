<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
define('ROOT_DIR', dirname(__FILE__).'/');
define('APP_DIR', ROOT_DIR.'app/');
define('WEB_ROOT', str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT_DIR));
include APP_DIR.'loader.php';