<?php

namespace App\Services;

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
    protected $apiUrl = 'https://instanda.atlassian.net/rest/api/2/';

    public function __construct()
    {
        $this->httpClient = new HttpClient();
    }
}
