<?php

namespace App\Facades;

use App\Services\FileService;
use Illuminate\Support\Facades\Facade;

/**
 * FileUploader facade
 */
class FileUploader extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FileService::class;
    }
}
