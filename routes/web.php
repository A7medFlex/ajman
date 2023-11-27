<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatGPTController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\LibraryController;
use App\Mail\ChatCreated;
use App\Mail\RegisterUser;
use App\Models\Blog;
use App\Models\Chat;
use App\Models\Event;
use App\Models\Library;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

Route::middleware('guest')->group(function() {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login/email', [LoginController::class, 'store'])->middleware('throttle:7,1');

    Route::get('/admin/login', [LoginController::class, 'create']);
    Route::post('/admin/login', [LoginController::class, 'store_admin'])->middleware('throttle:7,1');

    Route::get('/forget-password', [LoginController::class, 'forget_password']);
    Route::post('/forget_password', [LoginController::class, 'send_reset_password_link']);

    Route::get('/reset-password/{user}', [LoginController::class, 'reset_password'])
        ->name('reset-password')
        ->middleware('signed');

    Route::post('/change-password/{user}', [LoginController::class, 'update_password'])
        ->name('change-password')
        ->middleware('signed');
});


Route::middleware('auth')->group(function() {

    Route::post('/chatgpt', ChatGPTController::class);


    // admin routes
    Route::middleware('can:admin')->group(function() {
        // users
        Route::get('/admin/users', [AdminController::class, 'users']);
        Route::get('/admin/users/create', [AdminController::class, 'create_user']);
        Route::post('/admin/users', [AdminController::class, 'store_user']);
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit_user']);
        Route::patch('/admin/users/{user}', [AdminController::class, 'update_user']);
        Route::patch('/admin/users/{user}/password', [AdminController::class, 'update_user_password']);
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroy_user']);

        // tags
        Route::get('/admin/tags', [AdminController::class, 'tags']);
        Route::get('/admin/tags/create', [AdminController::class, 'create_tag']);
        Route::post('/admin/tags', [AdminController::class, 'store_tag']);
        Route::get('/admin/tags/{tag}/edit', [AdminController::class, 'edit_tag']);
        Route::patch('/admin/tags/{tag}', [AdminController::class, 'update_tag']);
        Route::delete('/admin/tags/{tag}', [AdminController::class, 'destroy_tag']);

        // dashbaord
        Route::get('/dashboard', function() {

            $released_chats_count = Chat::whereIsReleased(true)->count();
            $released_libraries_count = Library::whereIsReleased(true)->count();
            $released_blogs_count = Blog::whereIsReleased(true)->count();
            $released_events_count = Event::whereIsReleased(true)->count();

            $unreleased_chats_count = Chat::whereIsReleased(false)->count();
            $unreleased_libraries_count = Library::whereIsReleased(false)->count();
            $unreleased_blogs_count = Blog::whereIsReleased(false)->count();
            $unreleased_events_count = Event::whereIsReleased(false)->count();

            $users_count = User::count();
            $tags_count = Tag::count();


            return view('dashboard', [
                'released_chats_count' => $released_chats_count,
                'released_libraries_count' => $released_libraries_count,
                'released_blogs_count' => $released_blogs_count,
                'released_events_count' => $released_events_count,

                'unreleased_chats_count' => $unreleased_chats_count,
                'unreleased_libraries_count' => $unreleased_libraries_count,
                'unreleased_blogs_count' => $unreleased_blogs_count,
                'unreleased_events_count' => $unreleased_events_count,

                'users_count' => $users_count,
                'tags_count' => $tags_count,
            ]);
        });


        Route::get('/chats/{chat}/preview', [ChatController::class, 'preview']);
        Route::get('/libraries/{library}/preview', [LibraryController::class, 'preview']);
        Route::get('/blogs/{blog}/preview', [BlogController::class, 'preview']);
        Route::get('/events/{event}/preview', [EventsController::class, 'preview']);

        Route::get('/chats/unreleased', [ChatController::class, 'unreleased']);
        Route::get('/libraries/unreleased', [LibraryController::class, 'unreleased']);
        Route::get('/blogs/unreleased', [BlogController::class, 'unreleased']);
        Route::get('/events/unreleased', [EventsController::class, 'unreleased']);

        Route::patch('/libraries/{library}/release', [LibraryController::class, 'release']);
        Route::patch('/blogs/{blog}/release', [BlogController::class, 'release']);
        Route::patch('/events/{event}/release', [EventsController::class, 'release']);
        Route::patch('/chats/{chat}/release', [ChatController::class, 'release']);

        Route::delete('/libraries/{library}', [LibraryController::class, 'destroy']);
        Route::delete('/blogs/{blog}', [BlogController::class, 'destroy']);
        Route::delete('/events/{event}', [EventsController::class, 'destroy']);
        Route::delete('/chats/{chat}', [ChatController::class, 'destroy']);



    });

    Route::get('/', function () {
        $libraries = Library::whereIsReleased(true)->latest()->limit(20)->get();
        $blogs = Blog::whereIsReleased(true)->latest()->limit(20)->get();

        return view('home', compact('libraries', 'blogs'));
    });

    Route::view('/about', 'about');
    Route::view('/faq', 'faq');

    // library routes
    Route::get('/library', [LibraryController::class, 'index']);
    Route::get('/library/create', [LibraryController::class, 'create']);
    Route::post('/library', [LibraryController::class, 'store']);
    Route::get('/library/{library}', [LibraryController::class, 'show'])->name('library.show');
    Route::get('/library/{library}/edit', [LibraryController::class, 'edit']);
    Route::patch('/library/{library}', [LibraryController::class, 'update']);
    Route::post('/library/comments', [LibraryController::class, 'store_comment']);
    Route::patch('/libraries/{library}/likes/toggle', [LibraryController::class, 'togglelike']);

    // blog routes
    Route::get('/blog', [BlogController::class, 'index']);
    Route::get('/blog/create', [BlogController::class, 'create']);
    Route::post('/blog', [BlogController::class, 'store']);
    Route::get('/blog/{blog}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/{blog}/edit', [BlogController::class, 'edit']);
    Route::patch('/blog/{blog}', [BlogController::class, 'update']);
    // Route::delete('/blog/{blog}', [BlogController::class, 'destroy']);
    Route::post('/blog/comments', [BlogController::class, 'store_comment']);
    Route::patch('/blog/{blog}/likes/toggle', [BlogController::class, 'togglelike']);


    Route::delete('/attachments/{attachment}', [AttachmentsController::class, 'destroy']);

    // chats routes
    Route::get('/chats', [ChatController::class, 'index']);
    Route::get('/chats/create', [ChatController::class, 'create']);
    Route::post('/chats', [ChatController::class, 'store']);
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::patch('/chats/{chat}/change', [ChatController::class, 'change']);
    Route::post('/chats/{chat}/messages', [ChatController::class, 'store_message']);
    Route::patch('/chat/{chat}/likes/toggle', [ChatController::class, 'togglelike']);

    // account routes
    Route::get('/users/{user}', [AccountController::class, 'index']);
    Route::get('/users/{user}/edit', [AccountController::class, 'edit']);
    Route::patch('/users/{user}', [AccountController::class, 'update']);

    // search

    Route::get('/search', function() {

        $query = request('q');
        if(! $query) return back()  ;

        $libraries = Library::where('title_ar', 'LIKE', "%{$query}%")
            ->orWhere('title_en', 'LIKE', "%{$query}%")
            ->orWhere('body_ar', 'LIKE', "%{$query}%")
            ->orWhere('body_en', 'LIKE', "%{$query}%")
            ->get();
        $blogs = Blog::where('title_ar', 'LIKE', "%{$query}%")
            ->orWhere('title_en', 'LIKE', "%{$query}%")
            ->orWhere('body_ar', 'LIKE', "%{$query}%")
            ->orWhere('body_en', 'LIKE', "%{$query}%")
            ->get();

        $results = $libraries->concat($blogs);
        return back()->with([
            'results' => $results,
            'query' => $query
        ]);
    });

    Route::post('/logout', function() {
        auth()->logout();
        return redirect('/login');
    });

    // notifications

    Route::get('/notifications/{notification}/{chat}', function($notification, $chat) {
        $notification = auth()->user()->notifications()->findOrFail($notification);
        $notification->markAsRead();
        // dd($notification);
        return redirect("/chats/{$chat}");
    });
    Route::patch('/notifications/read/{notification}', function($notification) {
        $notification = auth()->user()->notifications()->findOrFail($notification);
        $notification->markAsRead();
        return redirect()->back();
    });

    Route::patch('/notifications', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    });

    // events
    Route::get('/events', [EventsController::class, 'index']);
    Route::get('/events/create', [EventsController::class, 'create']);
    Route::post('/events', [EventsController::class, 'store']);
    Route::get('/events/{event}', [EventsController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventsController::class, 'edit']);
    Route::patch('/events/{event}', [EventsController::class, 'update']);
    // Route::delete('/events/{event}', [EventsController::class, 'destroy']);
    Route::post('/event/comments', [EventsController::class, 'store_comment']);
    Route::patch('/event/{event}/likes/toggle', [EventsController::class, 'togglelike']);

    // uae pass

    // Route::get('/uaepass/login/callback', function(){
    //     $code = request('code');

    //     // Construct the cURL command to get the access token
    //     $command = [
    //         'bash',
    //         '-c',
    //         'OPENSSL_CONF=<(cat /etc/ssl/openssl.cnf ; echo Options = UnsafeLegacyRenegotiation) curl --location --request POST "https://stg-id.uaepass.ae/idshub/token" ' .
    //         '--header "Content-Type: multipart/form-data" ' .
    //         '--header "Authorization: Basic YWptX3BvbGljeV93ZWJfc3RnOkg3Q0RqSFVQcllTUk4yOFA=" ' .
    //         '--data "grant_type=authorization_code" ' .
    //         '--data "redirect_uri=http://localhost:8000/uaepass/callback" ' .
    //         '--data "code=' . $code . '"',
    //     ];

    //     // Create a new process
    //     $process = new Process($command);

    //     // Run the process
    //     $process->run();

    //     // Check if the process was successful
    //     if ($process->isSuccessful()) {
    //         // Decode the JSON response from the command output
    //         $response = json_decode($process->getOutput(), true);
    //         // Check if 'access_token' is present in the response
    //         if (isset($response['access_token'])) {
    //             // Your existing code to get user info using the access token
    //             $access_token = $response['access_token'];

    //             // Construct the cURL command to get user info
    //             $command = [
    //                 'bash',
    //                 '-c',
    //                 'OPENSSL_CONF=<(cat /etc/ssl/openssl.cnf ; echo Options = UnsafeLegacyRenegotiation) curl --location --request GET "https://stg-id.uaepass.ae/idshub/userinfo" ' .
    //                 '--header "Authorization: Bearer ' . $access_token . '"',
    //             ];

    //             // Create a new process for user info
    //             $processUserInfo = new Process($command);

    //             // Run the process for user info
    //             $processUserInfo->run();

    //             // Check if the process for user info was successful
    //             if ($processUserInfo->isSuccessful()) {
    //                 // Decode the JSON response from the command output
    //                 $user = json_decode($processUserInfo->getOutput(), true);

    //                 $user = User::where('uaepass_id', $user['uuid'])->first();

    //                 if(! $user) return redirect('/login')->with('error', 'لم يتم ربط الحساب بعد.');

    //                 auth()->login($user);
    //                 return redirect('/');

    //             } else {
    //                 // Handle the error for user info
    //                 throw new ProcessFailedException($processUserInfo);
    //             }
    //         } else {
    //             throw new \Exception('Access token not found in response.');
    //         }
    //     } else {
    //         // Handle the error for the first cURL command
    //         throw new ProcessFailedException($process);
    //     }
    // });
});

// Route::get('/lang/change/{lang}', function ($lang) {
//     if(! in_array($lang, ['ar', 'en'])) abort(400);

//     App::setLocale($lang);

//     return redirect()->back()->withCookie(Cookie::forever('locale', $lang));
// });


Route::get('/uaepass/callback', function(){
        $code = request('code');

        // Construct the cURL command to get the access token
        $command = [
            'bash',
            '-c',
            'OPENSSL_CONF=<(cat /etc/ssl/openssl.cnf ; echo Options = UnsafeLegacyRenegotiation) curl --location --request POST "https://stg-id.uaepass.ae/idshub/token" ' .
            '--header "Content-Type: multipart/form-data" ' .
            '--header "Authorization: Basic YWptX3BvbGljeV93ZWJfc3RnOkg3Q0RqSFVQcllTUk4yOFA=" ' .
            '--data "grant_type=authorization_code" ' .
            '--data "redirect_uri=http://localhost:8000/uaepass/callback" ' .
            '--data "code=' . $code . '"',
        ];

        // Create a new process
        $process = new Process($command);

        // Run the process
        $process->run();

        // Check if the process was successful
        if ($process->isSuccessful()) {
            // Decode the JSON response from the command output
            $response = json_decode($process->getOutput(), true);
            // Check if 'access_token' is present in the response
            if (isset($response['access_token'])) {
                // Your existing code to get user info using the access token
                $access_token = $response['access_token'];

                // Construct the cURL command to get user info
                $command = [
                    'bash',
                    '-c',
                    'OPENSSL_CONF=<(cat /etc/ssl/openssl.cnf ; echo Options = UnsafeLegacyRenegotiation) curl --location --request GET "https://stg-id.uaepass.ae/idshub/userinfo" ' .
                    '--header "Authorization: Bearer ' . $access_token . '"',
                ];

                // Create a new process for user info
                $processUserInfo = new Process($command);

                // Run the process for user info
                $processUserInfo->run();

                // Check if the process for user info was successful
                if ($processUserInfo->isSuccessful()) {
                    // Decode the JSON response from the command output
                    $user = json_decode($processUserInfo->getOutput(), true);

                    if(request('state') === 'login'){
                        $user = User::where('uaepass_id', $user['uuid'])->first();

                        if(! $user) return redirect('/login')->with('sucess', 'لم يتم ربط الحساب بعد.');

                        auth()->login($user);
                        return redirect('/');
                    }

                    // Your existing code to use the user data
                    auth()->user()->update([
                        'uaepass_id' => $user['uuid'],
                    ]);

                    return redirect('/')->with('success', 'تم ربط الحساب بنجاح');
                } else {
                    // Handle the error for user info
                    throw new ProcessFailedException($processUserInfo);
                }
            } else {
                throw new \Exception('Access token not found in response.');
            }
        } else {
            // Handle the error for the first cURL command
            throw new ProcessFailedException($process);
        }
    });
