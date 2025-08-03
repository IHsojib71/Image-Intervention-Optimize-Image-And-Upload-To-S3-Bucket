<?php

namespace App\Services;

use Illuminate\Support\Str;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService{
     protected $manager;

    public function __construct()
    {
        // Initialize the image manager with GD or Imagick driver
        $this->manager = new ImageManager(new Driver());
    }

      public function optimizeAndUpload($image, $path = 'Listings', $maxSize = 1024)
    {
        // Read the image file
        $img = $this->manager->read($image->getRealPath());

        // Get original dimensions
        $originalWidth = $img->width();
        $originalHeight = $img->height();
        $aspectRatio = $originalWidth / $originalHeight;

        // Calculate new dimensions
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;

        // Reduce dimensions until estimated size is below maxSize
        while ($this->estimateFileSize($newWidth, $newHeight) > $maxSize * 1024) {
            $newWidth = (int) ($newWidth * 0.9);
            $newHeight = (int) ($newWidth / $aspectRatio);

            if ($newWidth < 100 || $newHeight < 100) break;
        }

        // Resize the image
        $img->resize($newWidth, $newHeight);

        // Encode with quality adjustment
        $quality = 90;
        $encodedImage = $img->encodeByExtension($image->getClientOriginalExtension(), $quality);

        // Further reduce quality if needed
        while (strlen($encodedImage) > $maxSize * 1024 && $quality > 10) {
            $quality -= 5;
            $encodedImage = $img->encodeByExtension($image->getClientOriginalExtension(), $quality);
        }

        // Generate unique filename
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

        // Upload to S3
        Storage::disk('s3')->put($path . '/' . $filename, $encodedImage);

        return Storage::disk('s3')->url($path . '/' . $filename);
    }

    protected function estimateFileSize($width, $height)
    {
        return $width * $height * 3 * 0.5;
    }
}
