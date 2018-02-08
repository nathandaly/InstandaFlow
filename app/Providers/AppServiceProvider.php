<?php

namespace App\Providers;

use App\Contracts\CommentInterface;
use App\Contracts\JiraUser;
use App\Services\CommentService;
use App\Services\JiraUserService;
use App\Services\SlackMessageService;
use App\Services\SlackUsersService;
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
    }
}
