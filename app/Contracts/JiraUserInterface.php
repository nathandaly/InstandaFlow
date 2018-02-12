<?php

namespace App\Contracts;

interface JiraUserInterface
{
    /**
     * @param $username
     * @return string
     */
    public function getAuthorEmailFromUsername(string $username): string;
}
