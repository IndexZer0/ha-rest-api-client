<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\Fixtures;

class Fixtures
{
    public static function getDefaultStatusResponse(): array
    {
        return ['message' => 'API running.'];
    }

    public static function getDefaultConfigResponse(): array
    {
        return [
            "latitude"                => 51.22345398418073,
            "longitude"               => 6.794272770311183,
            "elevation"               => 0,
            "unit_system"             => [
                "length"                    => "km",
                "accumulated_precipitation" => "mm",
                "mass"                      => "g",
                "pressure"                  => "Pa",
                "temperature"               => "°C",
                "volume"                    => "L",
                "wind_speed"                => "m/s",
            ],
            "location_name"           => "Home",
            "time_zone"               => "Europe/London",
            "components"              => [
                "raspberry_pi",
                "counter",
                "switch",
                "script",
                "timer",
                'light',
            ],
            "config_dir"              => "/config",
            "whitelist_external_dirs" => [
                0 => "/config/www",
                1 => "/media",
            ],
            "allowlist_external_dirs" => [
                0 => "/config/www",
                1 => "/media",
            ],
            "allowlist_external_urls" => [],
            "version"                 => "2024.2.1",
            "config_source"           => "storage",
            "recovery_mode"           => false,
            "state"                   => "RUNNING",
            "external_url"            => null,
            "internal_url"            => null,
            "currency"                => "GBP",
            "country"                 => "GB",
            "language"                => "en-GB",
            "safe_mode"               => false,
        ];
    }

    public static function getCallServiceResponse(): array
    {
        return [
            [
                "entity_id"    => "light.bedroom_ceiling",
                "state"        => "on",
                "attributes"   => [
                    "min_color_temp_kelvin" => 2702,
                    "max_color_temp_kelvin" => 6535,
                    "min_mireds"            => 153,
                    "max_mireds"            => 370,
                    "effect_list"           => [
                        0 => "Slow Temp",
                        1 => "Stop",
                    ],
                    "supported_color_modes" => [
                        0 => "color_temp",
                    ],
                    "effect"                => null,
                    "color_mode"            => "color_temp",
                    "brightness"            => 102,
                    "color_temp_kelvin"     => 4694,
                    "color_temp"            => 213,
                    "hs_color"              => [
                        0 => 26.782,
                        1 => 23.566,
                    ],
                    "rgb_color"             => [
                        0 => 255,
                        1 => 221,
                        2 => 194,
                    ],
                    "xy_color"              => [
                        0 => 0.385,
                        1 => 0.354,
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
        ];
    }
}
