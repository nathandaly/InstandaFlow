<?php

namespace App;

/**
 * Class Authorization
 * @package App
 */
class Authorization
{
    /**
     * @var int
     */
    private $authType;

    /**
     * Authorization constructor.
     * @param int $authType
     */
    public function __construct(int $authType = HTTP_AUTH_BASIC)
    {
        $this->authType = $authType;
    }

    /**
     * @param array $credentials
     * @return array
     */
    protected function buildHeader(array $credentials) : array
    {
        $header = [];

        switch ($this->authType) {
            case HTTP_AUTH_BASIC:
                $header = [
                    'username' => $credentials['username'],
                    'password' => $credentials['password']
                ];
        }

        return $header;
    }
}