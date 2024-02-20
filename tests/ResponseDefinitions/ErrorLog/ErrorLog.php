<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ErrorLog;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class ErrorLog extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'text/plain',
            "2024-02-14 22:29:10.441 WARNING (SyncWorker_0) [homeassistant.loader] We found a custom integration browser_mod which has not been tested by Home Assistant. This component might cause stability problems, be sure to disable it if you experience issues with Home Assistant\n
2024-02-14 22:29:10.447 WARNING (SyncWorker_0) [homeassistant.loader] We found a custom integration hacs which has not been tested by Home Assistant. This component might cause stability problems, be sure to disable it if you experience issues with Home Assistant\n",
            'OK'
        );
    }
}
