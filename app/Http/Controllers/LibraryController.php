<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Library;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LibraryController extends Controller
{
    public function index()
    {
        $library = Library::latest("created_at");
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
            'attachments' => 'nullable|array',
            'attachments.*' => 'file'
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

        return redirect('/library')->with('success', __('layout.library_created'));
    }

    public function show(Library $library)
    {
        return view('library.show', ['library' => $library->load('attachments', 'tags')]);
    }

    public function edit(Library $library)
    {
        $this->authorize('update', $library);

        $tags = Tag::where('model', Library::class)->get();

        return view('library.edit', [
            'library' => $library->load('attachments', 'tags'),
            'tags' => $tags
        ]);
    }

    public function update(Library $library)
    {
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
            $attributes['thumbnail'] = request()->file('thumbnail')->store("thumbnails");
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
        $this->authorize('delete', $library);

        $library->delete();

        return redirect('/library')->with('success', __('layout.library_deleted'));
    }
}
