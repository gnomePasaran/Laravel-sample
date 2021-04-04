<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;

/**
 *  FileService class
 */
class FileService
{
    const SIZE_ORIGINAL = 0;
    const SIZE_MIN = 200;
    const SIZE_MEDIUM = 500;
    const SIZE_MAX = 1200;

    const SIZES = [
        self::SIZE_ORIGINAL,
        self::SIZE_MIN,
        self::SIZE_MEDIUM,
        self::SIZE_MAX,
    ];

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function storeFromHttp(UploadedFile $file): string
    {
        $storage = \Storage::disk('local');
        $name = str_random(15);
        $path = self::getPath($name);

        foreach (self::SIZES as $size) {
            $steam = Image::make($file)
                ->resize($size, null, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                })
                ->stream($file->extension());

            $storage->put($path.'/'.self::getFullName($name, $file->extension(), $size), $steam);
            $steam->close();
        }

        return (string) $storage->putFileAs($path, $file, self::getFullName($name, $file->extension()));
    }

    /**
     * @param string $path
     * @param int|null $size
     *
     * @return string
     */
    public function path(string $path, int $size = null): string
    {
        if (! $size || is_null($size)) {
            return $path;
        }

        if (! in_array($size, self::SIZES)) {
            return $path;
        }

        $extension = array_last(explode('.', $path));

        return str_replace_last($extension,$size.'.'.$extension, $path);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private static function getPath(string $name): string
    {
        $hash = substr($name, 0, 2);
        $year = Carbon::now()->year;

        return "images/{$year}/{$hash}";
    }

    /**
     * @param string $name
     * @param $extension
     * @param int|null $size
     *
     * @return string
     */
    private static function getFullName(string $name, $extension, int $size = null): string
    {
        return is_null($size)
            ? $name.'.'.$extension
            : $name.'.'.$size.'.'.$extension;
    }
}
