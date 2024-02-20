<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\HandleIntent;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class HandleIntentSuccess extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode([
                'speech' => [
                ],
                'card' => [
                ],
                'language' => 'en-GB',
                'response_type' => 'action_done',
                'data' => [
                    'targets' => [
                    ],
                    'success' => [
                    ],
                    'failed' => [
                    ],
                ],
            ]),
            "OK"
        );
    }
}
