<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendLoginLink;
use App\Models\LoginToken;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login.create');
    }

    public function mail()
    {
        request()->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', request('email'))->first();

        $random = bin2hex(random_bytes(32));

        $user->loginTokens()->create([
            'token' => $random,
            'expires_at' => now()->addMinutes(15)
        ]);

        $url = url('/login/session?'. http_build_query([
            '_id' => $user->id,
            '_token' => $random,
        ]));

        auth()->login($user);
        return redirect('/');

        // try{
        //     Mail::to(request('email'))
        //     ->send(new SendLoginLink($user, $url));
        // }catch(TransportException $e){
        //     // dd($e->getMessage());
        //     $user->loginTokens()->delete();
        //     return redirect('/login')->with('failed', 'Something went wrong, please contact your service provider.');
        // }

        // return redirect('/login')->with('success', 'We have just emailed you with your login link!, please check your inbox');

    }

    public function store()
    {
        if(! request('_id') || ! request('_token')) return redirect('/login');

        $token = LoginToken::validate([
            'user_id' => request('_id'),
            'token' => request('_token'),
        ]);

        $user = $token->user;

        $user->loginTokens()->delete();
        auth()->login($user);

        return redirect('/');
    }
}
