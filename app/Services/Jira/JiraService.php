<?php

namespace App\Services\Jira;

use GuzzleHttp\Client as HttpClient;

class JiraService
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = getenv('JIRA_API_URL') . '/rest/api/' . getenv('JIRA_API_VERSION') . '/';
        $this->httpClient = new HttpClient();
    }
}
