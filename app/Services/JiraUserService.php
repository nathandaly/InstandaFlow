<?php

namespace App\Services;

use App\Contracts\JiraUser;

/**
 * Class JiraUserService
 * @package App\Services
 */
class JiraUserService extends JiraService implements JiraUser
{
    /**
     * @param $username
     * @return array
     */
    public function getAuthorEmailFromUsername($username): array
    {
        $response = $this->httpClient->request('GET', $this->apiUrl . 'user', [
            'query' => [
                'username' => $username
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $authorDetails = json_decode($response->getBody());
            if (isset($authorDetails['emailAddress'])) {
                return  $authorDetails['emailAddress'];
            }
        }

        return null;
    }
}