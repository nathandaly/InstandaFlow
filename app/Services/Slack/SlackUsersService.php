<?php

namespace App\Services\Slack;

use App\Contracts\SlackUsersInterface;
use App\Exceptions\SlackRequestException;

/**
 * Class SlackUsersService
 * @package App\Services
 */
class SlackUsersService extends SlackService implements SlackUsersInterface
{
    /**
     * @param string $email
     * @return string
     * @throws SlackRequestException
     * @throws \Exception
     */
    public function lookupUserByEmail(string $email): string
    {
        $response = $this->httpClient->request('GET', $this->apiUrl . 'users.lookupByEmail', [
            'query' => [
                'token' => $this->apiToken,
                'email' => $email,
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $userDetails = json_decode($response->getBody(), true);
            if (isset($userDetails['user']['id'])) {
                return  $userDetails['user']['id'];
            }

            throw new SlackRequestException(json_encode($userDetails));
        }

        throw new \Exception('Unable to request user details.');
    }
}
