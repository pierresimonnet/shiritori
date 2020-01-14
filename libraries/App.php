<?php

class App
{
    public static function process()
    {
        $controllerName =  "word";
        $task = "index";

        if(!empty($_GET['controller'])){
            $controllerName = ucfirst(htmlentities($_GET['controller']));
        }
        if(!empty($_GET['task'])){
            $task = htmlentities($_GET['task']);
        }

        $controllerName = "\controllers\\" . $controllerName."Controller";
        $controller = new $controllerName();
        $controller->$task();
    }
}
