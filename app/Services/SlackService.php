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
    protected $apiUrl = 'https://slack.com/api/';

    /**
     * SlackService constructor.
     */
    public function __construct()
    {
        $this->httpClient = new HttpClient();
        $this->apiToken = 'xoxp-226446550871-270565397616-302605483975-db34d3e22ff49c16f381df602e978204';
    }
}
