<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Calendars;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class Calendars extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode([
                [
                    'name'      => 'Birthdays',
                    'entity_id' => 'calendar.birthdays',
                ],
            ]),
            'OK'
        );
    }
}
