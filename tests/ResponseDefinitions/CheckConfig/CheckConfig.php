<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\CheckConfig;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class CheckConfig extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode(["result" => "valid", "errors" => null, "warnings" => null]),
            'OK'
        );
    }
}
