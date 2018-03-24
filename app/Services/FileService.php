<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

/**
 *  FileService class
 */
class FileService
{
    public function storeFromHttp(UploadedFile $file)
    {
        $name = str_random(15).'.'.$file->getClientOriginalExtension();
        $relativePath = 'images/'.Carbon::now()->year;
        $path = storage_path('app/public/'.$relativePath);
        $file->move($path, $name);

        return ['name' => $name , 'file' => '/storage/'.$relativePath];
    }
}
