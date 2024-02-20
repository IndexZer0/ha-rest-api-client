<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Misc;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class NullJson extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            'null',
            'OK'
        );
    }
}
