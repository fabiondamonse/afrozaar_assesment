<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../autoload.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
if ((isset($uri[2]) && $uri[2] != 'category') || !isset($uri[3])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$class = "app\\Controller\\Api\\".ucwords($uri[2])."Controller";
$objFeedController = new $class();
$strMethodName = $uri[3] . 'Action';
$objFeedController->$strMethodName();