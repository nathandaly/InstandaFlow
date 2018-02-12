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
     * @param string $authType
     */
    public function __construct(string $authType = 'basic')
    {
        $this->authType = $authType;
    }

    /**
     * @param array $credentials
     * @return array
     */
    protected function buildHeader(array $credentials): array
    {
        $header = [];

        switch ($this->authType) {
            case 'basic':
                $header = [
                    $credentials['username'],
                    $credentials['password']
                ];
        }

        return $header;
    }
}
