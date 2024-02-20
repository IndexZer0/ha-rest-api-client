<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\CalendarEvents;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class CalendarEvents extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode([
                [
                    'start'         => [
                        'date' => '2024-02-15',
                    ],
                    'end'           => [
                        'date' => '2024-02-16',
                    ],
                    'summary'       => 'Friend Birthday',
                    'description'   => null,
                    'location'      => null,
                    'uid'           => null,
                    'recurrence_id' => null,
                    'rrule'         => null,
                ],
            ]),
            'OK'
        );
    }
}
