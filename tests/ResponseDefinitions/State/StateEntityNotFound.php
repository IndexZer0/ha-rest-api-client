<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\State;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class StateEntityNotFound extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            404,
            'application/json',
            json_encode(["message" => "Entity not found."]),
            'Not Found'
        );
    }
}
