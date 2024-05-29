<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(60 * 5);

/*
 * The above is the script configuration.
 * The following starts.
*/

header('Content-Type: text/html; charset=utf-8');
header('X-Frame-Options: deny');
header_remove('X-Powered-By');
//header('X-Content-Type-Options: nosniff');

const BASE_PATH = __DIR__;

require BASE_PATH . '/vendor/autoload.php';

require_once BASE_PATH . '/bootstrap/app.php';

run();