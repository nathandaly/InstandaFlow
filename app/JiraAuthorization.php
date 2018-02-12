<?php

namespace App;

use App\Contracts\AuthorizationInterface;

/**
 * Class JiraAuthorization
 * @package App
 */
class JiraAuthorization extends Authorization implements AuthorizationInterface
{
    /**
     * @return array
     */
    public function header(): array
    {
        $credentials = [
            'username' => env('JIRA_USERNAME'),
            'password' => env('JIRA_PASSWORD')
        ];

        return $this->buildHeader($credentials);
    }
}
