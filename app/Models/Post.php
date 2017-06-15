<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Answer;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content', 'published'];

    protected static function boot()
    {
        static::saving(function($model) {
            $model->slug = Post::seoUrl($model->title);
            $model->excerpt = substr($model->content, 0, 150);
            if ($model->published == true) {
              $model->published_at = Carbon::now()->toDateTimeString();
              $model->published = true;
            } else {
              $model->published = false;
            }
        });
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getPublishedPosts()
    {
        return $this->latest('published_at')->published()->get();
    }

    public function scopePublished($query)
    {
        $query->where('published_at', '<=', Carbon::now())
              ->where('published', '=', true);
    }

    private static function seoUrl($string)
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }
}
