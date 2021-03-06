<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StaticController extends Controller
{
    /**
     * Static page about us.
     *
     * @return mixed
     */
    public function aboutUs()
    {
        return Cache::rememberForever('about-us', function() {
            return view('pages.static.about-us')->render();
        });
    }
}
