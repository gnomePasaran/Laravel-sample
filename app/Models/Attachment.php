<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['file'];

    public function attachable()
    {
        return $this->morphTo();
    }
}
