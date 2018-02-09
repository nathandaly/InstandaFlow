<?php
/**
 * Created by PhpStorm.
 * User: Nathan
 * Date: 09/02/2018
 * Time: 01:47
 */

namespace App;

use App\Contracts\SubscriberInterface;

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
        $result = Unsubscribe::where([
            ['email', '=', $email],
            ['integration', '=', strtoupper($integration)],
            ['hook', '=', strtoupper($hook)],
        ])->count();

        return !! $result;
    }
}