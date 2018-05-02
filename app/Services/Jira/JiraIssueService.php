<?php

namespace App\Services\Jira;

use App\Contracts\JiraIssueInterface;
use App\JiraAuthorization;

/**
 * Class JiraIssueService
 * @package App\Services\Jira
 */
class JiraIssueService extends JiraService implements JiraIssueInterface
{
    /**
     * @param string $issueId
     * @param array $data
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function editIssueMeta(string $issueId, array $data): string
    {
        $response = $this->httpClient->request(
            'UPDATE',
            $this->apiUrl . 'issue' . '/' . $issueId,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'auth' => (new JiraAuthorization())->header(),
                ],
                'body' => \json_encode(['fields' => $data])
            ]
        );

        if ($response->getStatusCode() == 204) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }
}