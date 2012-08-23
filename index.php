<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
define('ROOT_DIR', dirname(__FILE__).'/');
define('APP_DIR', ROOT_DIR.'app/');

/* Make sure web_root doesn't have trailing slash */
$web_root = str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT_DIR);
if ($web_root[0] != '/') $web_root = '/' . $web_root;
$web_root = $web_root[strlen($web_root) - 1] == '/' ? substr($web_root, 0, strlen($web_root) - 1) : $web_root;
define('WEB_ROOT', $web_root);

include APP_DIR.'loader.php';