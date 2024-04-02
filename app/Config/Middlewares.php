<?php
namespace App\Config;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Src\Middleware\Middleware;
use Src\Middleware\MiddlewareException;
use Src\Response\JSONResponse;

Middleware::registerRequestMiddleware(0, function(Request $request): Request {
    // Example of a middleware that checks for authorization on a secret path
    $requestUri = $request->getUri();
    $headers = $request->getHeaders();


    // Example: Throw 401 Unauthorized if the request is not authorized
    // throw new MiddlewareException('Unauthorized', 401);

    return $request;
});

// Demonstrates a middleware that adds another field to the JSON Response
Middleware::registerResponseMiddleware(0, function(Response $response): Response {
    $jsonData = $response->getBody();
    $data = json_decode($jsonData, true);
    $data['response_length'] = strlen($jsonData);

    return new JSONResponse($data, $response->getStatusCode());
});