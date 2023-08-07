<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventsResource;
use App\Models\Attachment;
use App\Models\Event;
use Illuminate\Http\Request;

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
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Event $event)
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
        $event->delete();
        return redirect('/events')->with('success', __('layout.event_deleted'));
    }

    public function all_events()
    {
        $attributes = request()->validate([
            'month' => 'required|string',
            'year' => 'required|string'
        ]);

        if(request()->hasHeader('Authorization') && password_verify('kljfsa32oijasdkljew3#2@kl)*#', request()->header('Authorization'))){
            $events = EventsResource::collection(Event::whereMonth('start', request('month'))->whereYear('start', request('year'))->get());

            return response()->json($events, 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
