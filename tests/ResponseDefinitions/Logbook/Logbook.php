<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Logbook;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class Logbook extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode([
                [
                    'when'      => '2024-02-20T03:30:37.419923+00:00',
                    'state'     => 'off',
                    'entity_id' => 'light.bedroom_ceiling_nightlight',
                    'name'      => 'Bedroom Ceiling Nightlight',
                    'icon'      => 'mdi:weather-night',
                ],
                [
                    'when'      => '2024-02-20T04:10:23.441323+00:00',
                    'state'     => 'on',
                    'entity_id' => 'light.bedroom_ceiling_nightlight',
                    'name'      => 'Bedroom Ceiling Nightlight',
                    'icon'      => 'mdi:weather-night',
                ],
                [
                    'when'      => '2024-02-20T04:16:30.242207+00:00',
                    'state'     => 'off',
                    'entity_id' => 'light.bedroom_ceiling_nightlight',
                    'name'      => 'Bedroom Ceiling Nightlight',
                    'icon'      => 'mdi:weather-night',
                ],
            ]),
            'OK'
        );
    }
}
