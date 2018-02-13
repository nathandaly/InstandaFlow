<?php

namespace App\Providers;

use App\Contracts\CommentInterface;
use App\Contracts\SubscriberInterface;
use App\Services\CommentService;
use App\SubscriberRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CommentInterface::class, CommentService::class);
        $this->app->bind(SubscriberInterface::class, SubscriberRepository::class);
    }
}
