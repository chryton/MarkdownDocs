<?php

/* Make sure web_root doesn't have trailing slash */
$web_root = str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT_DIR);
if ($web_root[0] != '/') $web_root = '/' . $web_root;
$web_root = $web_root[strlen($web_root) - 1] == '/' ? substr($web_root, 0, strlen($web_root) - 1) : $web_root;
define('WEB_ROOT', $web_root);

/* Files necessary for run */
include 'third-party/markdown.php'; # Used for doc conversion
include 'third-party/simple_html_dom.php'; # Used for doc manipulation / nav extraction
include 'config.php'; # General config info
include 'helper.php';
include 'third-party/idiorm.php'; # Used for database transactions
include 'data_manager.php';
include 'page.php';
include 'router.php';
include 'app.php'; # Main app logic encapsulated here

/* Run the app */
App::run();
 