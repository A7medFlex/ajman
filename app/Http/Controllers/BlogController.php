<?php

namespace App\Http\Controllers;

use App\Mail\ReleasedEmail;
use App\Models\Attachment;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    public function index()
    {
        $blog = Blog::whereIsReleased(true)->latest("created_at");
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

        if(auth()->user()->is_admin) {
            $this->release($blog);
        }

        return redirect('/blog')->with('success', __('layout.blog_created'));
    }

    public function show(Blog $blog)
    {
        if(! $blog->is_released) abort(403);
        $blog->load('attachments', 'tags');
        $comments = $blog->comments()->latest()->with('user')->paginate(15);

        return view('blog.show', [
            'blog' => $blog,
            'comments' => $comments
        ]);
    }

    public function preview(Blog $blog)
    {
        $blog->load('user');

        return view('admin.preview.blogs', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        if(! $blog->is_released) abort(403);
        $this->authorize('update', $blog);

        $tags = Tag::where('model', Blog::class)->get();

        return view('blog.edit', [
            'blog' => $blog->load('attachments', 'tags'),
            'tags' => $tags
        ]);
    }

    public function update(Blog $blog)
    {
        if(! $blog->is_released) abort(403);
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
        // $this->authorize('delete', $blog);

        $blog->delete();

        return back()->with('success', __('layout.blog_deleted'));
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
            'commentable_type' => 'App\Models\Blog',
            'body' => request('body')
        ]);

        return redirect()->back();
    }

    public function togglelike(Blog $blog)
    {
        if(! $blog->is_released) abort(403);
        $blog->togglelike();
        return redirect()->back();
    }

    public function release(Blog $blog)
    {
        $blog->update(['is_released' => true]);

        Mail::to($blog->user->email)
            ->send(new ReleasedEmail(
                type: 'مدونة جديدة',
                url: route('blog.show', $blog),
                username: $blog->user->name
            ));

        return redirect('/dashboard')->with('success', 'تم نشر المدونة بنجاح.');
    }

    public function unreleased()
    {
        $blogs = Blog::where('is_released', false)->with('user')->latest();

        return view('admin.unreleased.blogs', [
            'blogs' => $blogs->paginate(10)
        ]);
    }
}
