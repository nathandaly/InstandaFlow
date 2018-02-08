<?php

namespace App\Contracts;

interface SlackMessageInterface
{
    /**
     * @param string $userId
     * @param string $text
     * @return array
     */
    public function postMessageToUser(string $userId, string $text): array;

    /**
     * @param string $channel
     * @param string $text
     * @return array
     */
    public function postMessageToChannel(string $channel, string $text): array;
}