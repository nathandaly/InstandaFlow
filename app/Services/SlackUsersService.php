<?php

namespace App\Services;

use App\Contracts\SlackUsers;

/**
 * Class SlackUsersService
 * @package App\Services
 */
class SlackUsersService extends SlackService implements SlackUsers
{
    /**
     * @param string $email
     * @return string
     */
    public function lookupUserByEmail(string $email) : string
    {
        $response = $this->httpClient->request('GET', $this->apiUrl . 'users.lookupByEmail', [
            'query' => [
                'token' => $this->apiToken,
                'email' => $email,
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $userDetails = json_decode($response->getBody());
            if (isset($userDetails['user']['id'])) {
               return  $userDetails['user']['id'];
            }
        }

        return null;
    }
}