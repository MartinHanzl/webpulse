<?php

namespace App\Http\Controllers;

use App\Services\FileManagerService;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    protected FileManagerService $fileManagerService;

    public function __construct(FileManagerService $fileManagerService)
    {
        $this->fileManagerService = $fileManagerService;
    }

    public function uploadImage(Request $request)
    {
        return $this->fileManagerService->uploadImage($request);
    }
}
