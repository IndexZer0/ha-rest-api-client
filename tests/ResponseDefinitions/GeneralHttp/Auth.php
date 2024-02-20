<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\GeneralHttp;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class Auth extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            401,
            'text/plain; charset=utf-8',
            '401: Unauthorized',
            'Unauthorized'
        );
    }
}
