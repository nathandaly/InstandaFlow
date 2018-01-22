<?php

namespace App\Contracts;

interface JiraUserInterface
{
    /**
     * @param $username
     * @return string
     */
    public function getAuthorEmailFromUsername($username) : string;
}