<?php

namespace App;

use App\Contracts\SubscriberInterface;

/**
 * Class SubscriberRepository
 * @package App
 */
class SubscriberRepository implements SubscriberInterface
{
    /**
     * @param string $email
     * @param string $integration
     * @param string $hook
     * @return bool
     */
    public function hasUnsubscribed(string $email, string $integration, string $hook): bool
    {
        $result = Subscriber::where([
            ['email', '=', $email],
            ['integration', '=', strtoupper($integration)],
            ['hook', '=', strtoupper($hook)],
        ])->count();

        return !! $result;
    }

    /**
     * @param string $email
     * @param string $integration
     * @param string $hook
     * @return bool
     */
    public function unsubscribe(string $email, string $integration, string $hook): bool
    {
        $subscriber = new Subscriber();
        $subscriber->email = $email;
        $subscriber->integration = $integration;
        $subscriber->hook = $hook;

        return $subscriber->save();
    }
}