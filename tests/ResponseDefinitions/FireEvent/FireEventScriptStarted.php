<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\FireEvent;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class FireEventScriptStarted extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode(["message" => "Event script_started fired."]),
            'OK'
        );
    }
}
