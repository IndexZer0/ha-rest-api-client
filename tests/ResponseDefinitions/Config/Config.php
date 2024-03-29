<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Config;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class Config extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode([
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
                    "/config/www",
                    "/media",
                ],
                "allowlist_external_dirs" => [
                    "/config/www",
                    "/media",
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
            ]),
            'OK'
        );
    }
}
