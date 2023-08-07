<?php

namespace App\View\Components;

use App\Models\Blog;
use App\Models\Library;
use App\Models\Tag;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Head extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $library_tags = Tag::where('model', Library::class)->get();
        $blog_tags = Tag::where('model', Blog::class)->get();

        return view('components.head', [
            'library_tags' => $library_tags,
            'blog_tags' => $blog_tags,
        ]);
    }
}
