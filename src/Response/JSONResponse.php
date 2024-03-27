<?php
namespace Src\Response;

use GuzzleHttp\Psr7\Response;

class JSONResponse extends Response
{
    public function __construct($data, $status = 200)
    {
        return parent::__construct(
            $status,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );
    }
}