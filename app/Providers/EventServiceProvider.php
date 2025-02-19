<?php

namespace App\Providers;

use App\Listeners\AcceptInvitationAfterRegistration;
use App\Listeners\SendEmailVerificationNotification;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            AcceptInvitationAfterRegistration::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Comment::observe(\App\Observers\CommentObserver::class);
        Task::observe(\App\Observers\TaskObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
