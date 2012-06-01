<?php

include 'config.php';
include 'markdown.php'; # Used for doc conversion
include 'simple_html_dom.php'; # Used for doc manipulation / nav extraction
include 'idiorm.php'; # Used for database transactions
include 'app.php';

App::run();
 