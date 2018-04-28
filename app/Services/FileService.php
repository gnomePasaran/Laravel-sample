<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Image;

/**
 *  FileService class
 */
class FileService
{
    const MIN_SIZE = 200;
    const MEDIUM_SIZE = 600;
    const MAX_SIZE = 1200;

    public function storeFromHttp(UploadedFile $file)
    {
        // $name = str_random(15).'.'.$file->getClientOriginalExtension();
        // $relativePath = 'images/'.Carbon::now()->year;
        // $path = storage_path('app/public/'.$relativePath);
        // $file->move($path, $name);
        // dd($image);

        $name = str_random(15).'.'.$file->getClientOriginalExtension();
        $path = storage_path('app/public/images/'.Carbon::now()->year);
        $image = Image::make($file->getRealPath());

        foreach ($this->getTypes() as $size) {
            $thumbName = name($name, $size, $image->mime());
            $thumb = $image->resize($size, $size, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::disk('local')->put($thumbName, $thumb->getContest());
        }
        Storage::disk('local')->put($name, $image->getContest());

        return ['name' => $name , 'file' => '/storage/'.$relativePath];
    }

    private function getTypes()
    {
        return [
            self::MIN_SIZE,
            self::MEDIUM_SIZE,
            self::MAX_SIZE,
        ];
    }

    private function name($name, $size, $extension)
    {
        return $name.'.'.$size.'.'.$extension;
    }
}
