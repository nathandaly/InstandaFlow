<?php

namespace App\Contracts;

/**
 * Interface SlackMessageInterface
 * @package App\Contracts
 */
interface SlackMessageInterface
{
    /**
     * @param string $userId
     * @param string $text
     * @param array $options
     * @return array
     */
    public function postMessageToUser(string $userId, string $text, array $options = []): array;

    /**
     * @param string $channel
     * @param string $text
     * @return array
     */
    public function postMessageToChannel(string $channel, string $text): array;
}