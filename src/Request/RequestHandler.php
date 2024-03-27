<?php

namespace Src\Request;

use App\Actions\Upload;
use GuzzleHttp\Psr7\Response;
use Src\Middleware\Middleware;
use Src\Middleware\MiddlewareException;
use Src\Response\JSONResponse;
use Src\Response\ResponseHandler;
use Src\Router\Router;

class RequestHandler
{
    protected \GuzzleHttp\Psr7\Request $request;

    protected function callRouter()
    {
        $callable = Router::getCallback($this->request->getMethod(), $this->request->getUri()->getPath());

        if ($callable === null) {
            return new JSONResponse(['error' => 'Route not found'], 404);
        }

        try {
            if (!is_callable($callable)) {
                $callable = new $callable();
                return $callable($this->request);
            }

            return $callable($this->request);
        } catch (\Exception $e) {
            return new JSONResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function handle(\GuzzleHttp\Psr7\Request $request): void
    {
        $responseHandler = new ResponseHandler();

        try {
            $request = Middleware::runRequestMiddlewares($request);
        } catch (MiddlewareException $e) {
            $responseHandler->handle(new JSONResponse(['error' => $e->getMessage()], $e->getCode()));
            return;
        }

        $this->request = $request;

        $response = $this->callRouter();

        $responseHandler->handle($response);
    }

}