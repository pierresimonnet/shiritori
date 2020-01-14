<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require '../libraries/Autoloader.php';
Autoloader::register();

App::process();
