<?php

namespace App\Providers;

use App\Contracts\SlackMessageInterface;
use App\Contracts\SlackUsersInterface;
use App\Services\Slack\SlackMessageService;
use App\Services\Slack\SlackUsersService;
use Illuminate\Support\ServiceProvider;

class SlackServiceProvider extends ServiceProvider
{
    /**
     * Register any Slack services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SlackUsersInterface::class, SlackUsersService::class);
        $this->app->bind(SlackMessageInterface::class, SlackMessageService::class);
    }
}
