<?php

namespace App\Http\Controllers;

use App\Facades\FileUploader;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'me', 'update']);
    }

    public function me()
    {
        $user = User::query()
            ->where(['id' => Auth::user()->id])
            ->with('photo', 'subscriptions')
            ->first();

        return view('pages.profile.me', ['user' => $user]);
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $setUser = $request->only('name');

        $user->update($setUser);
        if ($request->file('photo')) {
            $photo = FileUploader::storeFromHttp($request->file('photo'));
            $user->photo()->updateOrCreate(
                ['attachable_id' => $user->id, 'attachable_type' => User::class],
                $photo
            );
        }

        return redirect()->route('profile');
    }
}
