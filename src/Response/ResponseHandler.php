<?php

namespace Src\Response;

use GuzzleHttp\Psr7\Response;
use Src\Middleware\Middleware;
use Src\Middleware\MiddlewareException;

class ResponseHandler
{
    public function handle(\GuzzleHttp\Psr7\Response $response): void
    {
        // Run all middlewares
        try {
            $response = Middleware::runResponseMiddlewares($response);
        } catch (MiddlewareException $e) {
            $this->sendToClient(new JSONResponse(['error' => $e->getMessage()], $e->getCode()));
            return;
        }

        // Output the response
        $this->sendToClient($response);
    }

    // Actually output (echo) the response to the client (browser)
    protected function sendToClient(Response $response): void
    {
        // Output all Headers
        foreach ($response->getHeaders() as $name => $values) {
            header($name . ': ' . implode(', ', $values));
        }

        // Status Response Code
        http_response_code($response->getStatusCode());

        // Output the Body
        echo $response->getBody();
    }
}