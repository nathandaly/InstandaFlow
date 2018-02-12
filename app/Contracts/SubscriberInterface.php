<?php

namespace App\Contracts;

/**
 * Interface SubscriberInterface
 * @package App\Contracts
 */
interface SubscriberInterface
{
    /**
     * @param string $email
     * @param string $integration
     * @param string $hook
     * @return bool
     */
    public function hasUnsubscribed(string $email, string $integration, string $hook): bool;

    /**
     * @param string $email
     * @param string $integration
     * @param string $hook
     * @return bool
     */
    public function unsubscribe(string $email, string $integration, string $hook): bool;
}
