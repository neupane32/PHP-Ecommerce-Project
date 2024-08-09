<?php

class Router {
    public static function route($url) {
        $controllerName = $url[0] . 'Controller';
        $actionName = isset($url[1]) ? $url[1] : 'index';

        require_once '../app/controllers/' . $controllerName . '.php';
        $controller = new $controllerName();
        $controller->{$actionName}();
    }
}
