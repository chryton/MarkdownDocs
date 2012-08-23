<?php

/* Make sure web_root doesn't have trailing slash */
$web_root = str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT_DIR);
if ($web_root[0] != '/') $web_root = '/' . $web_root;
$web_root = $web_root[strlen($web_root) - 1] == '/' ? substr($web_root, 0, strlen($web_root) - 1) : $web_root;
define('WEB_ROOT', $web_root);

/* Files necessary for run */
include 'config.php'; # General config info
include 'idiorm.php'; # Used for database transactions
include 'app.php'; # App launcher

/* Run the app */
App::run();
 