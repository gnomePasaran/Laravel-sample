<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Attachment
 *
 * @property int $id
 * @property string $path
 * @property int $attachable_id
 * @property string $attachable_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $attachable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attachment whereAttachableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attachment whereAttachableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attachment whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attachment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attachment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Attachment extends Model
{
    protected $fillable = ['path'];

    /**
     * @return MorphTo
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return 'storage/'.$this->path;
    }
}
