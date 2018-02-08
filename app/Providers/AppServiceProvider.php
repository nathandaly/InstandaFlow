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
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Jira services
        $this->app->singleton(JiraUserService::class, function() {
            return new JiraUserService();
        });

        $this->app->singleton(SlackUsersService::class, function () {
           return new SlackUsersService();
        });

        $this->app->singleton(SlackMessageService::class, function () {
            return new SlackMessageService();
        });

        $this->app->singleton(CommentInterface::class, function () {
            return new CommentService();
        });

        // Slack services
        //$this->app->bind('App\Contracts\SlackUsers', 'App\Services\SlackUsersService');
        //$this->app->bind('App\Contracts\SlackMessage', 'App\Services\SlackMessageService');
    }
}
