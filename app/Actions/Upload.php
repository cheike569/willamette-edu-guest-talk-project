<?php

namespace App\Actions;

use App\Utils\ThumbnailGenerator;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\UploadedFile;
use Src\Database\Database;
use Src\Response\JSONResponse;

class Upload
{
    public function __invoke(Request $request): JSONResponse
    {
        // We want to store an uploaded file - Our Image
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->getUploadedFiles()['image'] ?? false;

        if (!$uploadedFile) {
            return new JSONResponse(['error' => 'No file uploaded'], 400);
        }

        // Validate File Type and Size
        if ($uploadedFile->getClientMediaType() !== 'image/jpeg' || $uploadedFile->getSize() > 1000000) {
            return new JSONResponse(['error' => 'Invalid file type or size (only jpgs under 10 MB are allowed)'], 400);
        }

        // Store
        $newFilename = uniqid() . '-' . $this->sanitizeFilename($uploadedFile->getClientFilename());
        $publicPath = __DIR__ . '/../../public';
        $uploadedPath = '/uploads/' . $newFilename;
        $thumbnailPath = '/thumbnails/' . $newFilename;

        $uploadedFile->moveTo($publicPath . $uploadedPath);

        // Generate Thumbnail
        ThumbnailGenerator::createThumbnail($publicPath . $uploadedPath, $publicPath . $thumbnailPath, thumbnailWidth: 150);

        // In a real world application, you should separate concerns like these
        $database = Database::getDatabase();
        $database->insert('images', [
            'path' => $uploadedPath,
            'thumbnail' => $thumbnailPath,
            'filesize' => $uploadedFile->getSize(),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return new JSONResponse([
            'path' => $uploadedPath,
            'thumbnail' => $thumbnailPath,
            'size' => $uploadedFile->getSize(),
            'message' => 'Your file was uploaded successfully!'
        ], 201); // HTTP 201 = Created
    }

    protected function sanitizeFilename($filename): array|string|null
    {
        return preg_replace('/[^a-zA-Z0-9-\.]/', '_', $filename);
    }
}