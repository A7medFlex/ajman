<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventsResource;
use App\Mail\ReleasedEmail;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EventsController extends Controller
{
    public function index()
    {
        $key = password_hash('kljfsa32oijasdkljew3#2@kl)*#', PASSWORD_DEFAULT);

        return view('events.index', compact('key'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'title' => 'required|string|min:4',
            'details' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'thumbnail' => 'nullable|image',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file'
        ]);

        $attributes['user_id'] = auth()->id();

        if(request()->hasFile('thumbnail')){
            $attributes['thumbnail'] = request()->file('thumbnail')->store("events/thumbnails");
        }

        $event = Event::create($attributes);

        if(request()->hasFile('attachments')){
            $attachments = [];

            foreach (request()->file('attachments') as $attachment) {
                $attachments[] = [
                    'name' => $attachment->getClientOriginalName(),
                    'extension' => $attachment->getClientOriginalExtension(),
                    'path' => $attachment->store("events/attachments"),
                    'attachable_type' => Event::class,
                    'attachable_id' => $event->id
                ];
            }

            Attachment::insert($attachments);
        }

        return redirect('/events')->with('success', __('layout.event_created'));
    }

    public function show(Event $event)
    {
        if(! $event->is_released) abort(403);
        $event->load('attachments');
        $comments = $event->comments()->latest()->with('user')->paginate(15);

        return view('events.show', [
            'event' => $event,
            'comments' => $comments
        ]);
    }

    public function preview(Event $event)
    {
        $event->load('user');

        return view('admin.preview.events', compact('event'));
    }

    public function edit(Event $event)
    {
        if(! $event->is_released) abort(403);
        if($event->user_id !== auth()->id()) abort(403);

        return view('events.edit', compact('event'));
    }

    public function update(Event $event)
    {
        if($event->user_id !== auth()->id()) abort(403);

        $attributes = request()->validate([
            'title' => 'required|string|min:4',
            'details' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'thumbnail' => 'nullable|image',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file'
        ]);

        if(request()->hasFile('thumbnail')){
            if($event->thumbnail) unlink(storage_path("app/public/{$event->thumbnail}"));
            $attributes['thumbnail'] = request()->file('thumbnail')->store("events/thumbnails");
        }

        $event->update($attributes);

        if(request()->hasFile('attachments')){
            $attachments = [];

            foreach (request()->file('attachments') as $attachment) {
                $attachments[] = [
                    'name' => $attachment->getClientOriginalName(),
                    'extension' => $attachment->getClientOriginalExtension(),
                    'path' => $attachment->store("attachments"),
                    'attachable_type' => Event::class,
                    'attachable_id' => $event->id
                ];
            }

            Attachment::insert($attachments);
        }

        return redirect('/events')->with('success', __('layout.event_updated'));

    }

    public function destroy(Event $event)
    {
        // if($event->user_id !== auth()->id()) abort(403);

        $event->delete();
        return back()->with('success', __('layout.event_deleted'));
    }

    public function all_events()
    {
        $attributes = request()->validate([
            'month' => 'required|string',
            'year' => 'required|string'
        ]);

        if(request()->hasHeader('Authorization') && password_verify('kljfsa32oijasdkljew3#2@kl)*#', request()->header('Authorization'))){
            $events = EventsResource::collection(Event::whereIsReleased(true)->whereMonth('start', request('month'))->whereYear('start', request('year'))->get());

            return response()->json($events, 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function store_comment()
    {
        request()->validate([
            'body' => 'required|string',
            'id' => 'required|numeric'
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'commentable_id' => request('id'),
            'commentable_type' => 'App\Models\Event',
            'body' => request('body')
        ]);

        return redirect()->back();
    }

    public function togglelike(Event $event)
    {
        if(! $event->is_released) abort(403);
        $event->togglelike();
        return redirect()->back();
    }

    public function release(Event $event)
    {
        $event->update(['is_released' => true]);

        Mail::to($event->user->email)
            ->send(new ReleasedEmail(
                type: 'فعالية جديدة',
                url: route('events.show', $event),
                username: $event->user->name
            ));

        return back()->with('success', 'تم نشر الفعالية بنجاح.');
    }

    public function unreleased()
    {
        $events = Event::where('is_released', false)->with('user')->latest();

        return view('admin.unreleased.events', [
            'events' => $events->paginate(10)
        ]);
    }
}

