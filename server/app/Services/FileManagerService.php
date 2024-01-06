<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image as Image;
use Intervention\Image\ImageManager;

class FileManagerService
{
    public function uploadImage(Request $request)
    {
        $file = $request->file('image');
        $fileId = uniqid();
        $extension = $file->getClientOriginalExtension();
        $filename = $fileId . '.' . $extension;

        $format = $request->get('format');
        $configFormats = config(sprintf('filemanager.image.%s', $format));

        if (!Storage::directoryExists($format)) {
            Storage::createDirectory($format);
        }

        foreach ($configFormats as $configFormat) {
            $folder = $configFormat['folder'];
            if (!Storage::directoryExists(sprintf('%s/%s', $format, $folder))) {
                Storage::createDirectory(sprintf('%s/%s', $format, $folder));
            }

            $image = $this->resizeImage($file, $configFormat['width'], $configFormat['height'], $extension);

            Storage::put(sprintf('%s/%s/%s', $format, $folder, $filename), file_get_contents($image->getPathname()));
        }

        return $filename;
    }

    private function resizeImage($uploadedFile, $width, $height, string $extension)
    {
        $image = ImageManager::imagick()->read($uploadedFile);

        $image->scale($width, $height);

        $tempPath = tempnam(sys_get_temp_dir(), 'resized_');
        $image->save($tempPath, 100, $extension);

        return new UploadedFile(
            $tempPath,
            $uploadedFile->getClientOriginalName(),
            $uploadedFile->getClientMimeType(),
            0,
        );
    }
}
