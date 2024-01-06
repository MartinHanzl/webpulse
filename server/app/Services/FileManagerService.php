<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
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
        $fileSize = round(($file->getSize() / 1024) / 1024, 2);

        if ($fileSize > env('FILE_MAX_SIZE')) {
            return Response::json([
                'success' => false,
                'errors' => ['Obrázek je větší než maximální povolená velikost.']
            ], 400);
        }

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

            // if we want to update something in database (must be specified in config file)
            if (array_key_exists('column', $configFormat) && $request->has('id')) {
                DB::table($format)->where('id', '=', $request->get('id'))->update([$configFormat['column'] => $filename]);
            }

            $image = $this->resizeImage($file, $configFormat['width'], $configFormat['height'], $extension);

            Storage::put(sprintf('%s/%s/%s', $format, $folder, $filename), file_get_contents($image->getPathname()));
        }

        return Response::json([
            'filename' => $filename
        ]);
    }

    private function resizeImage($uploadedFile, $width, $height, string $extension)
    {
        $image = ImageManager::imagick()->read($uploadedFile);

        $image->scale($width, $height);

        $tempPath = tempnam(sys_get_temp_dir(), 'resized_');
        $image->save($tempPath, 100);

        return new UploadedFile(
            $tempPath,
            $uploadedFile->getClientOriginalName(),
            $uploadedFile->getClientMimeType(),
            0,
        );
    }
}
