<?php

class Router {
    public static function route($url) {
        // Assuming the controller name is passed in $url[0]
        $controllerName = $url[0] . 'Controller';
        $actionName = isset($url[1]) ? $url[1] : 'index';

        // Adjusted path based on your folder structure
        require_once './controllers/' . $controllerName . '.php';

        // Instantiate the controller
        $controller = new $controllerName();
        // Call the action method
        $controller->{$actionName}();
    }
}
