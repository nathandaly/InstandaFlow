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
     * @param array $credentials
     * @return array
     */
    public function header(array $credentials): array
    {
        return $this->buildHeader($credentials);
    }
}