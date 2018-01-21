<?php

namespace App\Contracts;

interface AuthorizationInterface
{
    public function header(array $credentials) : array;
}