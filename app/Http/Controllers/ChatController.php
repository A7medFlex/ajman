<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Mail\ChatCreated;
use App\Models\Attachment;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Notifications\CommentPublished;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Notification;
use Symfony\Component\Mailer\Exception\TransportException;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::with('user')->latest();
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

        $users = User::where('id', '!=', auth()->id())->get();

        try{
            foreach ($users as $user){
                Mail::to($user->email)->send(new ChatCreated($chat));
            }
        }catch(TransportException $e){
            return back()->with('failed', 'حدث خطأ اثناء إرسال البريد الإلكتروني للمستخدمين.');
        }

        return redirect('/chats/' . $chat->id)->with('success', __('layout.chat_created'));
    }

    public function show(Chat $chat)
    {
        $chat->load(['messages', 'messages.user', 'messages.attachments', 'user']);
        $messages = $chat->messages()->latest("created_at")->paginate(10);

        return view('chats.show', compact('chat', 'messages'));
    }

    public function change(Chat $chat)
    {
        if($chat->user_id !== auth()->id()) abort(403);

        $chat->update(['is_open' => ! $chat->is_open]);

        return redirect('/chats/' . $chat->id)->with('success', __('layout.chat_updated'));
    }

    public function store_message(Chat $chat)
    {
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
}
