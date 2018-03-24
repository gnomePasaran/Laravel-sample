<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['name', 'file'];

    public function attachable()
    {
        return $this->morphTo();
    }

    public function getUrl()
    {
        return $this->file.'/'.$this->name;
    }
}
