<?php

namespace App\Services\Slack;

use App\Contracts\SlackMessageInterface;
use App\Exceptions\SlackRequestException;

/**
 * Class SlackMessageService
 * @package App\Services
 */
class SlackMessageService extends SlackService implements SlackMessageInterface
{
    /**
     * @param string $userId
     * @param string $text
     * @return array
     */
    public function postMessageToUser(string $userId, string $text) : array
    {
        return $this->postMessage($userId, $text);
    }

    /**
     * @param string $channel
     * @param string $text
     * @return array
     * @throws SlackRequestException
     */
    public function postMessageToChannel(string $channel, string $text) : array
    {
        $response = $this->postMessage($channel, $text);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody());
        }

        throw new SlackRequestException(json_encode($response->getBody()));
    }

    /**
     * @param string $subject
     * @param $text
     * @param array $options
     * @return array
     */
    private function postMessage(string $subject, $text, array $options = []) : array
    {
        $params = [
            'token' => $this->apiToken,
            'channel' => $subject,
            'text' => $text,
            'username' => 'InstandaFlow'
        ];

        array_merge($params, $options);

        $response = $this->httpClient->request(
            'POST',
            $this->apiUrl . 'chat.postMessage',
            [
                'form_params' => $params
            ]
        );

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }
}