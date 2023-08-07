<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Attachment;
use App\Models\Blog;
use App\Models\Library;
use App\Policies\AttachmentPolicy;
use App\Policies\BlogPolicy;
use App\Policies\LibraryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Blog::class => BlogPolicy::class,
        Library::class => LibraryPolicy::class,
        Attachment::class => AttachmentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
