<?php

function autoload($classname)
{
    $classname = str_replace("\\", "/", $classname);
    require_once dirname(__DIR__).DIRECTORY_SEPARATOR."$classname.php";
}

spl_autoload_register('autoload');
