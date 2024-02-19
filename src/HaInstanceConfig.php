<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient;

class HaInstanceConfig
{
    public function __construct(
        public string $host = 'localhost',
        public int $port = 8123,
        public string $endpoint = '/api/'
    ) {
    }

    public function getUrL(): string
    {
        return 'http://'.$this->host.':'.$this->port.$this->endpoint;
    }
}
