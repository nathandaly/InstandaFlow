<?php

namespace App\Services\Jira;

use App\Contracts\JiraUserInterface;
use App\JiraAuthorization;
use Illuminate\Http\Request;

/**
 * Class JiraUserService
 * @package App\Services
 */
class JiraUserService extends JiraService implements JiraUserInterface
{
    /**
     * @param $username
     * @return string
     */
    public function getAuthorEmailFromUsername(string $username): string
    {
        $response = $this->httpClient->request('GET', $this->apiUrl . 'user', [
            'auth' => (new JiraAuthorization())->header(),
            'query' => [
                'username' => $username
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $authorDetails = json_decode($response->getBody(), true);
            if (isset($authorDetails['emailAddress'])) {
                return $authorDetails['emailAddress'];
            }
        }

        return null;
    }
}