<?php
namespace App\Utils;

class ThumbnailGenerator
{
    public static function createThumbnail($sourceFile, $destFile, $thumbnailWidth = 150): void
    {
        // Load the image
        $sourceImage = \imagecreatefromjpeg($sourceFile);

        // Get the original image dimensions
        $originalWidth = \imagesx($sourceImage);
        $originalHeight = \imagesy($sourceImage);

        // Calculate the thumbnail height to maintain the aspect ratio
        $thumbnailHeight = (int)($originalHeight / $originalWidth * $thumbnailWidth);

        // Create a new true color image with the dimensions of the thumbnail
        $thumbnail = \imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

        // Copy and resize part of the original image with resampling to the thumbnail
        \imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $originalWidth, $originalHeight);

        // Output the thumbnail to the browser or file
        \imagejpeg($thumbnail, $destFile);

        // Free up memory
        \imagedestroy($sourceImage);
        \imagedestroy($thumbnail);
    }
}