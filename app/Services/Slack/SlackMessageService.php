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
     * @return array
     */
    public function postMessageToUser(string $userId, string $text, string $unsubscribeToken, array $options = []) : array
    {
        return $this->postMessage($userId, $text, $unsubscribeToken, $options = []);
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
    private function postMessage(string $subject, string $text, string $unsubscribeToken, array $options = []) : array
    {
        $params = [
            'token' => $this->apiToken,
            'channel' => $subject,
            'text' => $text,
            'attachments' => [
                [
                    'fallback' => 'Click here to unsubscribe http://165.227.230.69/' . $unsubscribeToken . '/unsubscribe',
                    'actions' => [
                        [
                            'type' => 'button',
                            'text' => 'Unsubscribe',
                            'style' => 'danger',
                            'url' => 'http://165.227.230.69/' . $unsubscribeToken . '/unsubscribe'
                        ]
                    ]
                ]
            ],
            'username' => 'InstandaFlow'
        ];

        array_merge($params, $options);

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