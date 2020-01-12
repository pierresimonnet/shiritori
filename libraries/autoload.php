<?php

function autoload($classname)
{
    $classname = str_replace("\\", "/", $classname);
    require_once "libraries/$classname.php";
}

spl_autoload_register('autoload');
