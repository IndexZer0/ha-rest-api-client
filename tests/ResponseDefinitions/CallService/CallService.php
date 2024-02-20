<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\CallService;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class CallService extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode([
                [
                    "entity_id"    => "light.bedroom_ceiling",
                    "state"        => "on",
                    "attributes"   => [
                        "min_color_temp_kelvin" => 2702,
                        "max_color_temp_kelvin" => 6535,
                        "min_mireds"            => 153,
                        "max_mireds"            => 370,
                        "effect_list"           => [
                            "Slow Temp",
                            "Stop",
                        ],
                        "supported_color_modes" => [
                            "color_temp",
                        ],
                        "effect"                => null,
                        "color_mode"            => "color_temp",
                        "brightness"            => 102,
                        "color_temp_kelvin"     => 4694,
                        "color_temp"            => 213,
                        "hs_color"              => [
                            26.782,
                            23.566,
                        ],
                        "rgb_color"             => [
                            255,
                            221,
                            194,
                        ],
                        "xy_color"              => [
                            0.385,
                            0.354,
                        ],
                        "flowing"               => false,
                        "music_mode"            => false,
                        "night_light"           => false,
                        "friendly_name"         => "Bedroom Ceiling",
                        "supported_features"    => 44,
                    ],
                    "last_changed" => "2024-02-19T17:46:00.080169+00:00",
                    "last_updated" => "2024-02-19T17:46:00.080169+00:00",
                    "context"      => [
                        "id"        => "context-id",
                        "parent_id" => null,
                        "user_id"   => "context-user-id",
                    ],
                ],
            ]),
            'OK'
        );
    }
}
