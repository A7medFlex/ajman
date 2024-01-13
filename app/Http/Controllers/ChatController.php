<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Mail\ChatCreated;
use App\Mail\ReleasedEmail;
use App\Mail\UnreleasedEmail;
use App\Models\Attachment;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Notifications\CommentPublished;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mailer\Exception\TransportException;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::where('is_released', true)->with('user')->latest();
        if(request()->has('status')){
            $chats->where('is_open', request('status'));
        }

        return view('chats.index', [
            'chats' => $chats->paginate(10)
        ]);
    }
    public function create()
    {
        return view('chats.create');
    }
    public function store()
    {
        $this->validate(request(), [
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $chat = Chat::create([
            'title' => request('title'),
            'description' => request('description'),
            'user_id' => auth()->id(),
        ]);

        if(auth()->user()->is_admin) {
            $this->release($chat);
        }

        return redirect('/chats/' . $chat->id)->with('success', __('layout.chat_created'));
    }

    public function show(Chat $chat)
    {
        if(! $chat->is_released) abort(403);

        $chat->load(['messages', 'messages.user', 'messages.attachments', 'user']);
        $messages = $chat->messages()->latest("created_at")->paginate(10);

        return view('chats.show', compact('chat', 'messages'));
    }

    public function preview(Chat $chat)
    {
        $chat->load('user');

        return view('admin.preview.chats', compact('chat'));
    }

    public function change(Chat $chat)
    {
        if(! $chat->is_released) abort(403);

        if($chat->user_id !== auth()->id()) abort(403);

        $chat->update(['is_open' => ! $chat->is_open]);

        return redirect('/chats/' . $chat->id)->with('success', __('layout.chat_updated'));
    }

    public function store_message(Chat $chat)
    {
        if(! $chat->is_released) abort(403);

        if(! $chat->is_open) abort(403);

        $this->validate(request(), [
            'message' => 'required|string|max:1000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file'
        ]);

        $message = $chat->messages()->create([
            'message' => request('message'),
            'user_id' => auth()->id(),
        ]);

        if(request()->hasFile('attachments')){
            $attachments = [];

            foreach (request()->file('attachments') as $attachment) {
                $attachments[] = [
                    'name' => $attachment->getClientOriginalName(),
                    'extension' => $attachment->getClientOriginalExtension(),
                    'path' => $attachment->store("attachments"),
                    'attachable_type' => Message::class,
                    'attachable_id' => $message->id
                ];
            }

            Attachment::insert($attachments);
        }

        $users = User::where('id', '!=', $message->user_id)->get();
        Notification::send($users, new CommentPublished($message->load(['user'])));

        broadcast(new CommentCreated($message->load(['user', 'attachments']), $message->created_at->diffForHumans()))->toOthers();

        return redirect('/chats/' . $chat->id );
    }

    // added
    public function destroy(Chat $chat)
    {
        $chat->delete();

        return back()->with('success', 'تم حذف المحادثة بنجاح.');
    }

    public function togglelike(Chat $chat)
    {
        if(! $chat->is_released) abort(403);

        $chat->togglelike();
        return redirect()->back();
    }

    public function release(Chat $chat)
    {
        $chat->update(['is_released' => true]);

        Mail::to($chat->user->email)
            ->send(new ReleasedEmail(
                type: 'محادثة جديدة',
                url: route('chat.show', $chat),
                username: $chat->user->name
            ));

        $users = User::where('id', '!=', $chat->user->id)->get();

        try{
            foreach ($users as $user){
                Mail::to($user->email)->send(new ChatCreated($chat));
            }
        }catch(TransportException $e){
            return back()->with('failed', 'حدث خطأ اثناء إرسال البريد الإلكتروني للمستخدمين.');
        }

        return redirect('/dashboard')->with('success', 'تم نشر المحادثة بنجاح.');
    }

    public  function unrelease(Chat $chat)
    {
        Mail::to($chat->user->email)
            ->send(new UnreleasedEmail(
                type: 'مكتبة',
                username: $chat->user->name,
                title: $chat->title
            ));

        return redirect('/dashboard')->with('success', 'تم إلغاء نشر المحادثة بنجاح.');
    }

    public function unreleased()
    {
        $chats = Chat::where('is_released', false)->with('user')->latest();

        return view('admin.unreleased.chats', [
            'chats' => $chats->paginate(10)
        ]);
    }

}
