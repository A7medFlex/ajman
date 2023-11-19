<?php

namespace App\Http\Controllers;

use App\Mail\RegisterUser;
use App\Models\Blog;
use App\Models\Library;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportException;

class AdminController extends Controller
{
    // Users Methods
    public function users()
    {
        $users = User::whereIsAdmin(false)->latest("created_at")->paginate(10);
        return view('admin.users.index', [
            'users' => $users
        ]);
    }
    public function create_user()
    {
        return view("admin.users.create");
    }

    public function store_user()
    {
        $attributes = request()->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'job_name' => 'nullable|string',
            'profile_image' => 'nullable|image'
        ]);
        if(request()->hasFile('profile_image')) $attributes['profile_image'] = request()->file("profile_image")->store("profile");

        // check if password contains at least one special character

        if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $attributes['password'])) {
            throw ValidationException::withMessages([
                'password' => 'كلمة المرور يجب أن تحتوي علي حرف واحد علي الأقل من  الرموز الخاصة',
            ]);
        }

        // added

        if(!request()->has('job_name')) $attributes['job_name'] = 'موظف';

        $user = User::create($attributes);

        try{
            Mail::to(request('email'))
            ->send(new RegisterUser($user, request('password')));
        }catch(TransportException $e){
            return back()->with('failed', 'حدث خطأ اثناء إرسال البريد الإلكتروني للمستخدم.');
        }


        return redirect('/admin/users')->with("success", __('layout.user_created'));
    }

    public function edit_user(User $user)
    {
        return view("admin.users.edit", [
            'user' => $user
        ]);
    }

    public function update_user(User $user)
    {
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
        return redirect('/admin/users')->with("success", __('layout.user_updated'));
    }

    public function update_user_password(User $user)
    {
        $attributes = request()->validate([
            'password' => 'required|string|min:5',
        ]);

        $user->update([
            'password' => $attributes['password'],
        ]);

        return redirect('/admin/users')->with("success", __('layout.password_updated'));
    }

    public function destroy_user(User $user)
    {
        if($user->profile_image) {
            $path = storage_path("app/public/{$user->profile_image}");
            if(file_exists($path)) unlink($path);
        }
        $user->delete();
        return redirect('/admin/users')->with("success", __('layout.user_deleted'));
    }

    // Tags Methods

    public function tags()
    {
        $tags = Tag::latest("created_at")->paginate(10);
        return view('admin.tags.index', [
            'tags' => $tags
        ]);
    }

    public function create_tag()
    {
        return view("admin.tags.create");
    }

    public function store_tag()
    {
        $models = [
            'library' => Library::class,
            'blog' => Blog::class,
        ];

        $attributes = request()->validate([
            'name' => 'required|string|min:3',
            'model' => 'required|in:library,blog',
        ]);

        Tag::create([
            'name' => $attributes['name'],
            'model' => $models[$attributes['model']],
        ]);

        return redirect('/admin/tags')->with("success", __('layout.tag_created'));
    }

    public function edit_tag(Tag $tag)
    {
        return view("admin.tags.edit", [
            'tag' => $tag
        ]);
    }

    public function update_tag(Tag $tag)
    {
        $models = [
            'library' => User::class,
            'blog' => User::class,
        ];

        $attributes = request()->validate([
            'name' => 'required|string|min:3',
            'model' => 'required|in:library,blog',
        ]);

        $tag->update([
            'name' => $attributes['name'],
            'model' => $models[$attributes['model']],
        ]);

        return redirect('/admin/tags')->with("success", __('layout.tag_updated'));

    }

    public function destroy_tag(Tag $tag)
    {
        $tag->delete();
        return redirect('/admin/tags')->with("success", __('layout.tag_deleted'));
    }
}
