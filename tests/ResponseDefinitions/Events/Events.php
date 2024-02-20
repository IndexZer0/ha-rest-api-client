<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Events;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class Events extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode([
                [
                    "event"          => "*",
                    "listener_count" => 1,
                ],
                [
                    "event"          => "entity_registry_updated",
                    "listener_count" => 8,
                ],
                [
                    "event"          => "homeassistant_start",
                    "listener_count" => 1,
                ],
                [
                    "event"          => "homeassistant_stop",
                    "listener_count" => 68,
                ],
                [
                    "event"          => "device_registry_updated",
                    "listener_count" => 3,
                ],
                [
                    "event"          => "homeassistant_close",
                    "listener_count" => 8,
                ],
                [
                    "event"          => "component_loaded",
                    "listener_count" => 3,
                ],
                [
                    "event"          => "user_removed",
                    "listener_count" => 1,
                ],
                [
                    "event"          => "homeassistant_final_write",
                    "listener_count" => 2,
                ],
                [
                    "event"          => "state_changed",
                    "listener_count" => 6,
                ],
                [
                    "event"          => "core_config_updated",
                    "listener_count" => 5,
                ],
                [
                    "event"          => "area_registry_updated",
                    "listener_count" => 2,
                ],
                [
                    "event"          => "service_registered",
                    "listener_count" => 1,
                ],
                [
                    "event"          => "service_removed",
                    "listener_count" => 1,
                ],
                [
                    "event"          => "logging_changed",
                    "listener_count" => 2,
                ],
                [
                    "event"          => "nodered",
                    "listener_count" => 1,
                ],
                [
                    "event"          => "tag_scanned",
                    "listener_count" => 1,
                ]
            ]),
            'OK'
        );
    }
}
