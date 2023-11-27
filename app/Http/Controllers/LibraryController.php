<?php

namespace App\Http\Controllers;

use App\Mail\ReleasedEmail;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Library;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class LibraryController extends Controller
{
    public function index()
    {
        $library = Library::whereIsReleased(true)->latest("created_at");

        if(request()->has('tag')) {
            $library->whereHas('tags', function ($query) {
                $query->where('id', request()->tag);
            });
        }

        return view('library.index', [
            'libraries' => $library->paginate(10),
            'tags' => Tag::where('model', Library::class)->get()
        ]);
    }

    public function create()
    {
        $tags = Tag::where('model', Library::class)->get();

        return view('library.create', compact('tags'));
    }

    public function store()
    {

        $attributes = request()->validate([
            'title_ar' => 'required|string|min:4',
            'title_en' => 'nullable|string|min:4',
            'body_ar' => 'required|string',
            'body_en' => 'nullable|string',
            'thumbnail' => 'nullable|image',
        ]);

        $attributes['user_id'] = auth()->id();

        if(request()->hasFile('thumbnail')){
            $attributes['thumbnail'] = request()->file('thumbnail')->store("thumbnails");
        }


        $library = Library::create($attributes);

        if(request()->hasFile('attachments')){
            $attachments = [];

            foreach (request()->file('attachments') as $attachment) {
                $attachments[] = [
                    'name' => $attachment->getClientOriginalName(),
                    'extension' => $attachment->getClientOriginalExtension(),
                    'path' => $attachment->store("attachments"),
                    'attachable_type' => Library::class,
                    'attachable_id' => $library->id
                ];
            }

            Attachment::insert($attachments);
        }

        $tags = explode(',', request()->tags);

        foreach ($tags as $tag){
            if(Tag::whereId($tag)->whereModel(Library::class)->exists()) $library->tags()->attach($tag);
            else ValidationException::withMessages(['tags' => 'The selected tag is invalid']);
        }

        if(auth()->user()->is_admin) {
            $this->release($library);
        }

        return redirect('/library')->with('success', __('layout.library_created'));
    }

    public function show(Library $library)
    {
        if(!$library->is_released) abort(404);

        $library->load('attachments', 'tags');
        $comments = $library->comments()->latest()->with('user')->paginate(15);

        return view('library.show', [
            'library' => $library,
            'comments' => $comments
        ]);
    }

    public function preview(Library $library)
    {
        $library->load('user');

        return view('admin.preview.libraries', compact('library'));
    }

    public function edit(Library $library)
    {
        if(!$library->is_released) abort(404);
        $this->authorize('update', $library);

        $tags = Tag::where('model', Library::class)->get();

        return view('library.edit', [
            'library' => $library->load('attachments', 'tags'),
            'tags' => $tags
        ]);
    }

    public function update(Library $library)
    {
        if(!$library->is_released) abort(404);
        $this->authorize('update', $library);

        $attributes = request()->validate([
            'title_ar' => 'required|string|min:4',
            'title_en' => 'nullable|string|min:4',
            'body_ar' => 'required|string',
            'body_en' => 'nullable|string',
            'thumbnail' => 'nullable|image',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file'
        ]);

        if(request()->hasFile('thumbnail')){

            if($library->thumbnail) unlink(storage_path("app/public/{$library->thumbnail}"));
            $attributes['thumbnail'] = request()->file('thumbnail')->store("thulibrarymbnails");
        }

        $library->update($attributes);

        if(request()->hasFile('attachments')){
            foreach (request()->file('attachments') as $attachment) {
                Attachment::create([
                    'name' => $attachment->getClientOriginalName(),
                    'extension' => $attachment->getClientOriginalExtension(),
                    'path' => $attachment->store("attachments"),
                    'attachable_type' => Library::class,
                    'attachable_id' => $library->id
                ]);
            }
        }

        $tags = explode(',', request()->tags);
        $library->tags()->detach($library->tags->whereNotIn('id', $tags)->pluck('id'));
        foreach ($tags as $tag){
            if(Tag::whereId($tag)->whereModel(Library::class)->exists()){
                if(!$library->tags->contains($tag)) $library->tags()->attach($tag);
            }
            else ValidationException::withMessages(['tags' => 'The selected tag is invalid']);
        }

        return redirect('/library')->with('success', __('layout.library_updated'));
    }

    public function destroy(Library $library)
    {
        // $this->authorize('delete', $library);

        $library->delete();

        return back()->with('success', __('layout.library_deleted'));
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
            'commentable_type' => 'App\Models\Library',
            'body' => request('body')
        ]);

        return redirect()->back();
    }

    public function togglelike(Library $library)
    {
        if(!$library->is_released) abort(404);
        $library->togglelike();
        return redirect()->back();
    }

    public function release(Library $library)
    {
        $library->update(['is_released' => true]);

        Mail::to($library->user->email)
            ->send(new ReleasedEmail(
                type: 'مكتبة جديدة',
                url: route('library.show', $library),
                username: $library->user->name
            ));

        return redirect('/dashboard')->with('success', 'تم نشر المكتبة بنجاح.');

    }

    public function unreleased()
    {
        $libraries = Library::where('is_released', false)->with('user')->latest();

        return view('admin.unreleased.libraries', [
            'libraries' => $libraries->paginate(10)
        ]);
    }
}
