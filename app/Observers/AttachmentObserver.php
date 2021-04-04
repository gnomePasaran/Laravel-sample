<?php

namespace App\Observers;

use App\Facades\FileUploader;
use App\Models\Attachment;
use App\Services\FileService;

class AttachmentObserver
{
    /**
     * Handle the attachment "deleting" event.
     *
     * @param Attachment $attachment
     *
     * @return void
     */
    public function deleting(Attachment $attachment)
    {
        $paths = collect([]);

        foreach (FileService::SIZES as $size) {
            $paths->push(FileUploader::path($attachment->path, $size));
        }

        $paths->each(function (string $path) {
            \Storage::disk('local')->delete('public/'.$path);
        });
    }
}
