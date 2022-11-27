<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Register the autoloader
spl_autoload_register(function($classname) {
    include "classes/$classname.php";
});

session_start();

// Parse the query string for command
$command = "login";
if (isset($_GET["command"])) {
    $command = $_GET["command"];
}

// Instantiate the controller and run
$controller = new OutfitController($command);
$controller->run();


// <meta http-equiv="refresh" content='0; url=login.php'
?>