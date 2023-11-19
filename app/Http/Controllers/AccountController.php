<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(User $user)
    {
        if(request('content') == 'chats')
            $content = $user->chats()->latest()->paginate(10);
        elseif(request('content') == 'blogs')
            $content = $user->blogs()->latest()->paginate(10);
        else
            $content = $user->libraries()->latest()->paginate(10);


        return view('account.index', compact('user', 'content'));
    }

    public function edit(User $user)
    {
        if($user->id !== auth()->id()) abort(403);

        return view('account.edit', compact('user'));
    }

    public function update(User $user)
    {
        if($user->id !== auth()->id()) abort(403);

        $attributes = request()->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'job_name' => 'required|string',
            'profile_image' => 'nullable|image'
        ]);
        if(request()->hasFile('profile_image')) {
            $attributes['profile_image'] = request()->file("profile_image")->store("profile");
            if($user->profile_image) {
                $path = storage_path("app/public/{$user->profile_image}");
                if(file_exists($path)) unlink($path);
            }
        }

        $user->update($attributes);
        return redirect('/users/'. $user->id)->with("success", __('layout.account_updated'));
    }
}
