<?php

namespace App\Config;

use App\Actions\Upload;
use GuzzleHttp\Psr7\Request;
use Src\Database\Database;
use Src\Response\JSONResponse;
use Src\Router\Router;

// Callable Class
Router::registerRoute(Router::POST, '/api/thumbnail', Upload::class);

// Inline Callbacks are also possible!
Router::registerRoute(Router::GET, '/api/thumbnail', function (Request $request) {
    $database = Database::getDatabase();

    $id = intval($_GET['id']) ?? null;

    if ($id) {
        $thumbnail = $database->select("images", '*', [
            "id[=]" => $id
        ])[0] ?? null;
    }

    if (empty($thumbnail)) {
        return new JSONResponse(['error' => 'Thumbnail not found'], 404);
    }

    return new JSONResponse($thumbnail);
});