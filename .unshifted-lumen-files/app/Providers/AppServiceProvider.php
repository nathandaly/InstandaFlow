<?php

namespace App\Providers;

use App\Contracts\CommentInterface;
use App\Services\CommentService;
use App\Contracts\SubscriberInterface;
use App\SubscriberRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**\e
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Jira services
        $this->app->bind(CommentInterface::class, CommentService::class);
        $this->app->bind(SubscriberInterface::class, SubscriberRepository::class);
    }
}
