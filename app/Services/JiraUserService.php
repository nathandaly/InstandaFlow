<?php

namespace App\Services;

use App\Authorization;
use App\Contracts\JiraUserInterface;

/**
 * Class JiraUserService
 * @package App\Services
 */
class JiraUserService extends JiraService implements JiraUserInterface
{
    /**
     * @param $username
     * @return array
     */
    public function getAuthorEmailFromUsername($username): array
    {
        $response = $this->httpClient->request('GET', $this->apiUrl . 'user', [
            'auth' => (new Authorization(HTTP_AUTH_BASIC))->header([
                'username' => env('JIRA_USERNAME'),
                'password' => env('JIRA_PASSWORD')
            ]),
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