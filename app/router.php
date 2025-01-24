<?php

class Router {

    public static function get($route, $controller) {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            self::route($route, $controller);
        }
    }

    public static function post($route, $controller) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            self::route($route, $controller);
        }
    }

    public static function delete($route, $controller) {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            self::route($route, $controller);
        }
    }

    public static function put($route, $controller) {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            self::route($route, $controller);
        }
    }
    public static function any() {
        self::callController('LostController');
        exit();
    }

    private static function callController($controller, array $parameters = []) {
        include_once ('src/controllers/' . $controller . '.php');
        $object = new $controller($parameters);
        $object->call();
        exit();
    }


    private static function route($route, $controller) {
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');

        $route_parts = explode('/', $route);
        $request_url_parts = explode('/', $request_url);
        array_shift($route_parts);
        array_shift($request_url_parts);


        if ($route_parts[0] == '' && count($request_url_parts) == 0){
            self::callController($controller);
            return;
        }

        if (count($route_parts) != count($request_url_parts)) {
            return;
        }

        $parameters = [];
        for ($i = 0; $i < count($route_parts); $i++) {
            $route_part = $route_parts[$i];
            if (preg_match("/^[$]/", $route_part)) {
                $route_part = ltrim($route_part, '$');
                $parameters[$route_part] = $request_url_parts[$i];
            } else if ($route_parts[$i] != $request_url_parts[$i]) {
                return;
            }
        }

        self::callController($controller, $parameters);
        exit();
    }
}