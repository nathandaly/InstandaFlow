<?php

namespace App\Contracts;

interface SlackUsers
{
    /**
     * @param string $email
     * @return string
     */
    public function lookupUserByEmail(string $email): string;
}