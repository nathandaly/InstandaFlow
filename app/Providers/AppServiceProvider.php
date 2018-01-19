<?php

namespace App\Providers;

use App\Contracts\JiraUser;
use App\Services\JiraUserService;
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
        $this->app->singleton(JiraUser::class, function() {
            return new JiraUserService();
        });

        // Slack services
        //$this->app->bind('App\Contracts\SlackUsers', 'App\Services\SlackUsersService');
        //$this->app->bind('App\Contracts\SlackMessage', 'App\Services\SlackMessageService');
    }
}
