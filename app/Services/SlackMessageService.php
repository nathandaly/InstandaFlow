<?php

namespace App\Services;

use App\Contracts\SlackMessage;

/**
 * Class SlackMessageService
 * @package App\Services
 */
class SlackMessageService implements SlackMessage
{
    /**
     * @param string $userId
     * @param string $text
     * @return array
     */
    public function postMessageToUser(string $userId, string $text) : array
    {
        $response = $this->postMessage($userId, $text);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody());
        }

        return null;
    }

    /**
     * @param string $channel
     * @param string $text
     * @return array
     */
    public function postMessageToChannel(string $channel, string $text) : array
    {
        $response = $this->postMessage($channel, $text);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody());
        }

        return null;
    }

    /**
     * @param string $subject
     * @param $text
     * @param array $options
     * @return array
     */
    private function postMessage(string $subject, $text, array $options = []) : array
    {
        $query = [
            'token' => $this->apiToken,
            'channel' => $subject,
            'text' => $text,
            'username' => 'InstandaFlow'
        ];

        array_merge($query, $options);

        $response = $this->httpClient->request(
            'GET',
            $this->apiUrl . 'chat.postMessage',
            $query
        );

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody());
        }

        return null;
    }
}