<?php
namespace Src;

use GuzzleHttp\Psr7\ServerRequest;
use Src\Request\RequestHandler;

class Kernel {
    // Load Config Files
    public function bootstrap()
    {
        // Load Config Files
        require_once __DIR__ . '/../app/Config/Middlewares.php';
        require_once __DIR__ . '/../app/Config/Routes.php';
    }

    public function handleRequest(): void
    {
        $serverRequest = ServerRequest::fromGlobals();

        $requestHandler = new RequestHandler();
        $requestHandler->handle($serverRequest);
    }
}