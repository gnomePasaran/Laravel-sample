<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Answer;
use App\Models\Attachment;
use App\Models\Post;
use Illuminate\Http\UploadedFile;

class AttachmentService
{
    /**
     * @param Post|Answer $entity
     * @param array $files
     *
     * @return Answer|Post
     */
    public function saveAttachments($entity, array $files)
    {
        $attachIDs = [];

        foreach ($files as $index => $file) {
            if (! $file instanceof UploadedFile) {
                $attachIDs[] = $index;
                continue;
            }

            $uploadedFile = \FileUploader::storeFromHttp($file);

            /** @var Attachment $attach */
            $attach = $entity->attachments()->create(['path' => $uploadedFile]);
            $attachIDs[] = $attach->id;
        }

        return $entity->syncAttachment($attachIDs);
    }
}
