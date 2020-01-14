<?php

function autoload($classname)
{
    $classname = str_replace("\\", "/", $classname);
    require_once "$classname.php";
}

spl_autoload_register('autoload');
