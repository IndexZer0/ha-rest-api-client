<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\GeneralHttp;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class ServerError extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            500,
            'text/plain; charset=utf-8',
            '400: Bad Request',
            'Bad Request'
        );
    }
}
