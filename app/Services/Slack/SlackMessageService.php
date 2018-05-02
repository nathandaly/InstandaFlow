<?php

namespace App\Services\Slack;

use App\Contracts\SlackMessageInterface;
use App\Exceptions\SlackRequestException;
use App\SlackAuthorization;
use App\SubscriberRepository;

/**
 * Class SlackMessageService
 * @package App\Services
 */
class SlackMessageService extends SlackService implements SlackMessageInterface
{
    /**
     * @param string $userId
     * @param string $text
     * @param array $options
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function postMessageToUser(string $userId, string $text, array $options = []) : array
    {
        return $this->postMessage($userId, $text, $options);
    }

    /**
     * @param string $channel
     * @param string $text
     * @return array
     * @throws SlackRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
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
     * @param string $text
     * @param array $options
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function postMessage(string $subject, string $text, array $options = []) : array
    {
        $params = [
            'token' => $this->apiToken,
            'channel' => $subject,
            'text' => $text,
            'username' => 'InstandaFlow'
        ];

        $params = array_merge($params, $options);

        $response = $this->httpClient->request(
            'POST',
            $this->apiUrl . 'chat.postMessage',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiToken,
                ],
                'body' => \json_encode($params)
            ]
        );

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }
}