<?php

namespace App\Providers;

use App\Contracts\JiraUserInterface;
use App\Services\Jira\JiraUserService;
use Illuminate\Support\ServiceProvider;

class JiraServiceProvider extends ServiceProvider
{
    /**
     * Register any Jira services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(JiraUserInterface::class, JiraUserService::class);
    }
}
