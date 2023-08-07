<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    public function index()
    {
        $blog = Blog::latest("created_at");
        if(request()->has('tag')) {
            $blog->whereHas('tags', function ($query) {
                $query->where('id', request()->tag);
            });
        }

        return view('blog.index', [
            'blogs' => $blog->paginate(10),
            'tags' => Tag::where('model', Blog::class)->get()
        ]);
    }

    public function create()
    {
        $tags = Tag::where('model', Blog::class)->get();

        return view('blog.create', compact('tags'));
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


        $blog = Blog::create($attributes);

        if(request()->hasFile('attachments')){
            foreach (request()->file('attachments') as $attachment) {
                Attachment::create([
                    'name' => $attachment->getClientOriginalName(),
                    'extension' => $attachment->getClientOriginalExtension(),
                    'path' => $attachment->store("attachments"),
                    'attachable_type' => Blog::class,
                    'attachable_id' => $blog->id
                ]);
            }
        }

        $tags = explode(',', request()->tags);

        foreach ($tags as $tag){
            if(Tag::whereId($tag)->whereModel(Blog::class)->exists()) $blog->tags()->attach($tag);
            else ValidationException::withMessages(['tags' => 'The selected tag is invalid']);
        }

        return redirect('/blog')->with('success', __('layout.blog_created'));
    }

    public function show(Blog $blog)
    {
        return view('blog.show', ['blog' => $blog->load('attachments', 'tags')]);
    }

    public function edit(Blog $blog)
    {
        $this->authorize('update', $blog);

        $tags = Tag::where('model', Blog::class)->get();

        return view('blog.edit', [
            'blog' => $blog->load('attachments', 'tags'),
            'tags' => $tags
        ]);
    }

    public function update(Blog $blog)
    {
        $this->authorize('update', $blog);

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

            if($blog->thumbnail) unlink(storage_path("app/public/{$blog->thumbnail}"));
            $attributes['thumbnail'] = request()->file('thumbnail')->store("thumbnails");
        }

        $blog->update($attributes);

        if(request()->hasFile('attachments')){
            $attachments = [];

            foreach (request()->file('attachments') as $attachment) {
                $attachments[] = [
                    'name' => $attachment->getClientOriginalName(),
                    'extension' => $attachment->getClientOriginalExtension(),
                    'path' => $attachment->store("attachments"),
                    'attachable_type' => Blog::class,
                    'attachable_id' => $blog->id
                ];
            }

            Attachment::insert($attachments);
        }

        $tags = explode(',', request()->tags);
        $tagsToAttach = [];

        $blog->tags()->detach($blog->tags->whereNotIn('id', $tags)->pluck('id'));
        foreach ($tags as $tag){
            if(Tag::whereId($tag)->whereModel(Blog::class)->exists()){
                if(!$blog->tags->contains($tag)) $tagsToAttach[] = $tag;
            }
            else ValidationException::withMessages(['tags' => 'The selected tag is invalid']);
        }

        $blog->tags()->attach($tagsToAttach);

        return redirect('/blog')->with('success', __('layout.blog_updated'));
    }

    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);

        $blog->delete();

        return redirect('/blog')->with('success', __('layout.blog_deleted'));
    }
}
