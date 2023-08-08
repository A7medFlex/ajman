<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\LibraryController;
use App\Models\Blog;
use App\Models\Library;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function() {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login/email', [LoginController::class, 'mail']);
    Route::get('/login/session', [LoginController::class, 'store'])
        ->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function() {

    // admin routes
    Route::middleware('can:admin')->group(function() {
        // users
        Route::get('/admin/users', [AdminController::class, 'users']);
        Route::get('/admin/users/create', [AdminController::class, 'create_user']);
        Route::post('/admin/users', [AdminController::class, 'store_user']);
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit_user']);
        Route::patch('/admin/users/{user}', [AdminController::class, 'update_user']);
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroy_user']);

        // tags
        Route::get('/admin/tags', [AdminController::class, 'tags']);
        Route::get('/admin/tags/create', [AdminController::class, 'create_tag']);
        Route::post('/admin/tags', [AdminController::class, 'store_tag']);
        Route::get('/admin/tags/{tag}/edit', [AdminController::class, 'edit_tag']);
        Route::patch('/admin/tags/{tag}', [AdminController::class, 'update_tag']);
        Route::delete('/admin/tags/{tag}', [AdminController::class, 'destroy_tag']);
    });

    Route::get('/', function () {
        $libraries = Library::latest()->limit(20)->get();
        $blogs = Blog::latest()->limit(20)->get();

        return view('home', compact('libraries', 'blogs'));
    });

    // library routes
    Route::get('/library', [LibraryController::class, 'index']);
    Route::get('/library/create', [LibraryController::class, 'create']);
    Route::post('/library', [LibraryController::class, 'store']);
    Route::get('/library/{library}', [LibraryController::class, 'show']);
    Route::get('/library/{library}/edit', [LibraryController::class, 'edit']);
    Route::patch('/library/{library}', [LibraryController::class, 'update']);
    Route::delete('/library/{library}', [LibraryController::class, 'destroy']);

    // blog routes
    Route::get('/blog', [BlogController::class, 'index']);
    Route::get('/blog/create', [BlogController::class, 'create']);
    Route::post('/blog', [BlogController::class, 'store']);
    Route::get('/blog/{blog}', [BlogController::class, 'show']);
    Route::get('/blog/{blog}/edit', [BlogController::class, 'edit']);
    Route::patch('/blog/{blog}', [BlogController::class, 'update']);
    Route::delete('/blog/{blog}', [BlogController::class, 'destroy']);


    Route::delete('/attachments/{attachment}', [AttachmentsController::class, 'destroy']);

    // chats routes
    Route::get('/chats', [ChatController::class, 'index']);
    Route::get('/chats/create', [ChatController::class, 'create']);
    Route::post('/chats', [ChatController::class, 'store']);
    Route::get('/chats/{chat}', [ChatController::class, 'show']);
    Route::patch('/chats/{chat}/change', [ChatController::class, 'change']);
    Route::post('/chats/{chat}/messages', [ChatController::class, 'store_message']);

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
    Route::delete('/events/{event}', [EventsController::class, 'destroy']);

});

// Route::get('/lang/change/{lang}', function ($lang) {
//     if(! in_array($lang, ['ar', 'en'])) abort(400);

//     App::setLocale($lang);

//     return redirect()->back()->withCookie(Cookie::forever('locale', $lang));
// });

