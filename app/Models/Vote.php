<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'score'];

    public function votable()
    {
        return $this->morphTo();
    }
}
