<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\States;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class States extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode([
                [
                    'entity_id'    => 'light.bedroom_ceiling',
                    'state'        => 'off',
                    'attributes'   => [
                        'min_color_temp_kelvin' => 2702,
                        'max_color_temp_kelvin' => 6535,
                        'min_mireds'            => 153,
                        'max_mireds'            => 370,
                        'effect_list'           => [
                            'Slow Temp',
                            'Stop',
                        ],
                        'supported_color_modes' => [
                            'color_temp',
                        ],
                        'effect'                => null,
                        'color_mode'            => null,
                        'brightness'            => null,
                        'color_temp_kelvin'     => null,
                        'color_temp'            => null,
                        'hs_color'              => null,
                        'rgb_color'             => null,
                        'xy_color'              => null,
                        'flowing'               => false,
                        'music_mode'            => false,
                        'night_light'           => true,
                        'friendly_name'         => 'Bedroom Ceiling',
                        'supported_features'    => 44,
                    ],
                    'last_changed' => '2024-02-19T17:59:01.968173+00:00',
                    'last_updated' => '2024-02-19T17:59:01.968173+00:00',
                    'context'      => [
                        'id'        => 'context-id',
                        'parent_id' => null,
                        'user_id'   => 'context-user-id',
                    ],
                ],
                [
                    'entity_id'    => 'light.bedroom_ceiling_nightlight',
                    'state'        => 'off',
                    'attributes'   => [
                        'supported_color_modes' => [
                            'brightness',
                        ],
                        'color_mode'            => null,
                        'brightness'            => null,
                        'flowing'               => false,
                        'music_mode'            => false,
                        'night_light'           => true,
                        'icon'                  => 'mdi:weather-night',
                        'friendly_name'         => 'Bedroom Ceiling Nightlight',
                        'supported_features'    => 0,
                    ],
                    'last_changed' => '2024-02-20T04:16:30.242207+00:00',
                    'last_updated' => '2024-02-20T04:16:30.242207+00:00',
                    'context'      => [
                        'id'        => 'context-id',
                        'parent_id' => null,
                        'user_id'   => null,
                    ],
                ],
                [
                    'entity_id'    => 'media_player.bedroom_tv',
                    'state'        => 'off',
                    'attributes'   => [
                        'friendly_name'      => 'KD-43XG8396',
                        'supported_features' => 152461,
                    ],
                    'last_changed' => '2024-02-20T08:12:06.918516+00:00',
                    'last_updated' => '2024-02-20T08:12:06.918516+00:00',
                    'context'      => [
                        'id'        => 'context-id',
                        'parent_id' => null,
                        'user_id'   => null,
                    ],
                ],
            ]),
            'OK'
        );
    }
}
