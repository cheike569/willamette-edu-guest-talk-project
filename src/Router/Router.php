<?php
namespace Src\Router;

class Router {

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];

    public static function registerRoute($method, $path, $callback) {
        self::$routes[$method][$path] = $callback;
    }

    public static function isRouteRegistered($method, $path): bool
    {
        return isset(self::$routes[$method][$path]);
    }

    public static function getCallback($method, $path) {
        if(!self::isRouteRegistered($method, $path)) {
            return null;
        }

        return self::$routes[$method][$path];
    }
}