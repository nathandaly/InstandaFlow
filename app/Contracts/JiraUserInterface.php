<?php

namespace App\Contracts;

interface JiraUserInterface
{
    /**
     * @param $username
     * @return array
     */
    public function getAuthorEmailFromUsername($username) : array;
}