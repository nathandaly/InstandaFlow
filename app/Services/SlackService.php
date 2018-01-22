<?php

namespace App\Services;

use GuzzleHttp\Client as HttpClient;

class SlackService
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $apiToken;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * SlackService constructor.
     */
    public function __construct()
    {
        $this->httpClient = new HttpClient();
        $this->apiUrl = env('SLACK_API_URL');
        $this->apiToken = env('SLACK_API_TOKEN');
    }
}
