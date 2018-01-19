<?php

namespace App\Contracts;

interface JiraUser
{
    /**
     * @param $username
     * @return array
     */
    public function getAuthorEmailFromUsername($username) : array;
}