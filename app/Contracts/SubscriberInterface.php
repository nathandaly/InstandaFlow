<?php

namespace App\Contracts;

interface SubscriberInterface
{
    /**
     * @param string $email
     * @param string $integration
     * @param string $hook
     * @return bool
     */
    public function hasUnsubscribed(string $email, string $integration, string $hook): bool;
}