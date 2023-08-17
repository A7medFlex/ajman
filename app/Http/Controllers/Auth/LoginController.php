<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendLoginLink;
use App\Models\LoginToken;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login.create');
    }

    // public function mail()
    // {
    //     request()->validate([
    //         'email' => ['required', 'email', 'exists:users,email'],
    //     ]);

    //     $user = User::where('email', request('email'))->first();

    //     $random = bin2hex(random_bytes(32));

    //     $user->loginTokens()->create([
    //         'token' => $random,
    //         'expires_at' => now()->addMinutes(15)
    //     ]);

    //     $url = url('/login/session?'. http_build_query([
    //         '_id' => $user->id,
    //         '_token' => $random,
    //     ]));

    //     // auth()->login($user);
    //     // return redirect('/');

        // try{
        //     Mail::to(request('email'))
        //     ->send(new SendLoginLink($user, $url));
        // }catch(TransportException $e){
        //     // dd($e->getMessage());
        //     $user->loginTokens()->delete();
        //     return redirect('/login')->with('failed', 'حدث خطأ أثناء إرسال رابط تسجيل الدخول، حاول مرة أخرى');
        // }

    //     return redirect('/login')->with('success', 'تم إرسال رابط تسجيل الدخول إلى بريدك الإلكتروني');

    // }

    public function store()
    {
        // if(! request('_id') || ! request('_token')) return redirect('/login');

        // $token = LoginToken::validate([
        //     'user_id' => request('_id'),
        //     'token' => request('_token'),
        // ]);

        // $user = $token->user;

        // $user->loginTokens()->delete();
        // auth()->login($user);

        // return redirect('/');

        request()->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:5'],
        ]);

        $user = User::where('email', request('email'))->first();

        if(!password_verify(request('password'), $user->password)){
            throw ValidationException::withMessages([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
            ]);
        }

        auth()->login($user);

        return redirect('/');
    }
}
