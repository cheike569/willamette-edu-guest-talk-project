<?php
namespace Src\Middleware;

use Src\Response\JSONResponse;

class Middleware {

    static array $requestMiddlewares = [];
    static array $responseMiddlewares = [];


    public static function registerRequestMiddleware(int $priority, Callable $callback) {
        self::$requestMiddlewares[$priority] = $callback;
    }

    public static function getRequestMiddlewares(): array {
        ksort(self::$requestMiddlewares);
        return self::$requestMiddlewares;
    }

    public static function runRequestMiddlewares(\GuzzleHttp\Psr7\Request $request): \GuzzleHttp\Psr7\Request {
        $middlewares = self::getRequestMiddlewares();
        foreach($middlewares as $middleware) {
            $request = $middleware($request);
        }
        return $request;
    }

    // @todo This could be refactored to avoid duplicate code fragments, but is left separate for demonstration purposes
    public static function registerResponseMiddleware(int $priority, Callable $callback) {
        self::$responseMiddlewares[$priority] = $callback;
    }

    public static function getResponseMiddlewares(): array {
        ksort(self::$responseMiddlewares);
        return self::$responseMiddlewares;
    }

    public static function runResponseMiddlewares(\GuzzleHttp\Psr7\Response $response): \GuzzleHttp\Psr7\Response
    {
        $middlewares = self::getResponseMiddlewares();
        foreach($middlewares as $middleware) {
            $response = $middleware($response);
        }
        return $response;
    }

}