<?php

namespace App\Contracts;

interface SlackUsersInterface
{
    /**
     * @param string $email
     * @return string
     */
    public function lookupUserByEmail(string $email): string;
}
