<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\History;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class History extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode([
                [
                    [
                        'entity_id'    => 'light.bedroom_ceiling',
                        'state'        => 'off',
                        'attributes'   => [
                            'effect'            => null,
                            'color_mode'        => null,
                            'brightness'        => null,
                            'color_temp_kelvin' => null,
                            'color_temp'        => null,
                            'hs_color'          => null,
                            'rgb_color'         => null,
                            'xy_color'          => null,
                            'flowing'           => false,
                            'music_mode'        => false,
                            'night_light'       => true,
                            'friendly_name'     => 'Bedroom Ceiling',
                        ],
                        'last_changed' => '2024-02-18T23:16:22.487462+00:00',
                        'last_updated' => '2024-02-18T23:16:22.487462+00:00',
                        'context'      => [
                            'id'        => 'context-id',
                            'parent_id' => null,
                            'user_id'   => null,
                        ],
                    ],
                    [
                        'entity_id'    => 'light.bedroom_ceiling',
                        'state'        => 'unavailable',
                        'attributes'   => [
                            'friendly_name' => 'Bedroom Ceiling',
                        ],
                        'last_changed' => '2024-02-19T13:08:55.212425+00:00',
                        'last_updated' => '2024-02-19T13:08:55.212425+00:00',
                        'context'      => [
                            'id'        => 'context-id',
                            'parent_id' => null,
                            'user_id'   => null,
                        ],
                    ],
                    [
                        'entity_id'    => 'light.bedroom_ceiling',
                        'state'        => 'off',
                        'attributes'   => [
                            'effect'            => null,
                            'color_mode'        => null,
                            'brightness'        => null,
                            'color_temp_kelvin' => null,
                            'color_temp'        => null,
                            'hs_color'          => null,
                            'rgb_color'         => null,
                            'xy_color'          => null,
                            'flowing'           => false,
                            'music_mode'        => false,
                            'night_light'       => true,
                            'friendly_name'     => 'Bedroom Ceiling',
                        ],
                        'last_changed' => '2024-02-19T13:08:55.427160+00:00',
                        'last_updated' => '2024-02-19T13:08:55.427160+00:00',
                        'context'      => [
                            'id'        => 'context-id',
                            'parent_id' => null,
                            'user_id'   => null,
                        ],
                    ],
                    [
                        'entity_id'    => 'light.bedroom_ceiling',
                        'state'        => 'on',
                        'attributes'   => [
                            'effect'            => null,
                            'color_mode'        => 'color_temp',
                            'brightness'        => 102,
                            'color_temp_kelvin' => 4694,
                            'color_temp'        => 213,
                            'hs_color'          => [
                                26.782,
                                23.566,
                            ],
                            'rgb_color'         => [
                                255,
                                221,
                                194,
                            ],
                            'xy_color'          => [
                                0.385,
                                0.354,
                            ],
                            'flowing'           => false,
                            'music_mode'        => false,
                            'night_light'       => false,
                            'friendly_name'     => 'Bedroom Ceiling',
                        ],
                        'last_changed' => '2024-02-19T17:46:00.080169+00:00',
                        'last_updated' => '2024-02-19T17:46:00.080169+00:00',
                        'context'      => [
                            'id'        => 'context-id',
                            'parent_id' => null,
                            'user_id'   => null,
                        ],
                    ],
                    [
                        'entity_id'    => 'light.bedroom_ceiling',
                        'state'        => 'off',
                        'attributes'   => [
                            'effect'            => null,
                            'color_mode'        => null,
                            'brightness'        => null,
                            'color_temp_kelvin' => null,
                            'color_temp'        => null,
                            'hs_color'          => null,
                            'rgb_color'         => null,
                            'xy_color'          => null,
                            'flowing'           => false,
                            'music_mode'        => false,
                            'night_light'       => true,
                            'friendly_name'     => 'Bedroom Ceiling',
                        ],
                        'last_changed' => '2024-02-19T17:46:20.724683+00:00',
                        'last_updated' => '2024-02-19T17:46:20.724683+00:00',
                        'context'      => [
                            'id'        => 'context-id',
                            'parent_id' => null,
                            'user_id'   => null,
                        ],
                    ],
                    [
                        'entity_id'    => 'light.bedroom_ceiling',
                        'state'        => 'on',
                        'attributes'   => [
                            'effect'            => null,
                            'color_mode'        => 'color_temp',
                            'brightness'        => 102,
                            'color_temp_kelvin' => 4694,
                            'color_temp'        => 213,
                            'hs_color'          => [
                                26.782,
                                23.566,
                            ],
                            'rgb_color'         => [
                                255,
                                221,
                                194,
                            ],
                            'xy_color'          => [
                                0.385,
                                0.354,
                            ],
                            'flowing'           => false,
                            'music_mode'        => false,
                            'night_light'       => false,
                            'friendly_name'     => 'Bedroom Ceiling',
                        ],
                        'last_changed' => '2024-02-19T17:58:59.510886+00:00',
                        'last_updated' => '2024-02-19T17:58:59.510886+00:00',
                        'context'      => [
                            'id'        => 'context-id',
                            'parent_id' => null,
                            'user_id'   => null,
                        ],
                    ],
                    [
                        'entity_id'    => 'light.bedroom_ceiling',
                        'state'        => 'off',
                        'attributes'   => [
                            'effect'            => null,
                            'color_mode'        => null,
                            'brightness'        => null,
                            'color_temp_kelvin' => null,
                            'color_temp'        => null,
                            'hs_color'          => null,
                            'rgb_color'         => null,
                            'xy_color'          => null,
                            'flowing'           => false,
                            'music_mode'        => false,
                            'night_light'       => true,
                            'friendly_name'     => 'Bedroom Ceiling',
                        ],
                        'last_changed' => '2024-02-19T17:59:01.968173+00:00',
                        'last_updated' => '2024-02-19T17:59:01.968173+00:00',
                        'context'      => [
                            'id'        => 'context-id',
                            'parent_id' => null,
                            'user_id'   => null,
                        ],
                    ],
                ],
            ]),
            'OK'
        );
    }
}
