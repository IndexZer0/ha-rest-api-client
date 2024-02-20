<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\GeneralHttp;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class BadRequest extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            400,
            'text/plain; charset=utf-8',
            "500 Internal Server Error

Server got itself in trouble",
            'Internal Server Error'
        );
    }
}
