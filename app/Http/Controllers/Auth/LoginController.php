<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendLoginLink;
use App\Mail\SendResetLink;
use App\Models\LoginToken;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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
            // 'g-recaptcha-response' => ['required', function (string $attribute, mixed $value, Closure $fail) {
            //     $g_response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            //         'secret' => config('services.recaptcha.secret_key'),
            //         'response' => $value,
            //         'remoteip' => request()->ip(),
            //     ]);
            //     if(!$g_response->json('success')) $fail('الرجاء إعادة المحاولة');
            // }],
        ]);

        $user = User::where('email', request('email'))
            ->where('is_admin', 0)
            ->first();

        if(!$user || !password_verify(request('password'), $user->password)){
            throw ValidationException::withMessages([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
            ]);
        }

        auth()->login($user);

        return redirect('/');
    }

    public function store_admin()
    {
        request()->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:5'],
            // 'g-recaptcha-response' => ['required', function (string $attribute, mixed $value, Closure $fail) {
            //     $g_response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            //         'secret' => config('services.recaptcha.secret_key'),
            //         'response' => $value,
            //         'remoteip' => request()->ip(),
            //     ]);
            //     if(!$g_response->json('success')) $fail('الرجاء إعادة المحاولة');
            // }],
        ]);

        $user = User::where('email', request('email'))
            ->where('is_admin', 1)
            ->first();

        if(!$user || !password_verify(request('password'), $user->password)){
            throw ValidationException::withMessages([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
            ]);
        }

        auth()->login($user);

        return redirect('/');
    }

    public function forget_password()
    {
        return view('auth.login.forget_password');
    }

    public function send_reset_password_link()
    {
        request()->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', request('email'))->first();

        $url = URL::temporarySignedRoute(
            'reset-password',
            now()->addMinutes(30),
            $user->id
        );

        try{
            Mail::to(request('email'))
            ->send(new SendResetLink($user, $url));
        }catch(TransportException $e){
            return redirect('/forget-password')->with('failed', 'حدث خطأ أثناء إرسال رابط إعادة تعيين كلمة المرور، حاول مرة أخرى');
        }

        return redirect('/forget-password')->with('success', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني');
    }

    public function reset_password(User $user)
    {
        return view('auth.login.reset_password', compact('user'));
    }

    public function update_password(User $user)
    {
        request()->validate([
            'password' => ['required', 'min:8', 'string'],
        ]);

        if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', request('password'))) {
            throw ValidationException::withMessages([
                'password' => 'كلمة المرور يجب أن تحتوي علي حرف واحد علي الأقل من  الرموز الخاصة',
            ]);
        }

        $user->update([
            'password' => request('password'),
        ]);

        return redirect('/login')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}
