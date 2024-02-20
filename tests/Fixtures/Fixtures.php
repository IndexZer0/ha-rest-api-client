<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\Fixtures;

class Fixtures
{
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
        ];
    }

    public static function getEventsResponse(): array
    {
        // code: 200
        // reason: OK
        return [
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
        ];
    }

    public static function getServicesResponse(): array
    {
        return [
            [
                'domain'   => 'light',
                'services' => [
                    'turn_on'  => [
                        'name'        => 'Turn on',
                        'description' => 'Turn on one or more lights and adjust properties of the light, even when they are turned on already.',
                        'fields'      => [
                            'transition'          => [
                                'filter'      => [
                                    'supported_features' => [
                                        32,
                                    ],
                                ],
                                'selector'    => [
                                    'number' => [
                                        'min'                 => 0,
                                        'max'                 => 300,
                                        'unit_of_measurement' => 'seconds',
                                    ],
                                ],
                                'name'        => 'Transition',
                                'description' => 'Duration it takes to get to next state.',
                            ],
                            'rgb_color'           => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'selector'    => [
                                    'color_rgb' => null,
                                ],
                                'name'        => 'Color',
                                'description' => 'The color in RGB format. A list of three integers between 0 and 255 representing the values of red, green, and blue.',
                            ],
                            'rgbw_color'          => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'example'     => '[255, 100, 100, 50]',
                                'selector'    => [
                                    'object' => null,
                                ],
                                'name'        => 'RGBW-color',
                                'description' => 'The color in RGBW format. A list of four integers between 0 and 255 representing the values of red, green, blue, and white.',
                            ],
                            'rgbww_color'         => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'example'     => '[255, 100, 100, 50, 70]',
                                'selector'    => [
                                    'object' => null,
                                ],
                                'name'        => 'RGBWW-color',
                                'description' => 'The color in RGBWW format. A list of five integers between 0 and 255 representing the values of red, green, blue, cold white, and warm white.',
                            ],
                            'color_name'          => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'select' => [
                                        'translation_key' => 'color_name',
                                        'options'         => [
                                            'homeassistant',
                                            'aliceblue',
                                            'antiquewhite',
                                            'aqua',
                                            'aquamarine',
                                            'azure',
                                            'beige',
                                            'bisque',
                                            'blanchedalmond',
                                            'blue',
                                            'blueviolet',
                                            'brown',
                                            'burlywood',
                                            'cadetblue',
                                            'chartreuse',
                                            'chocolate',
                                            'coral',
                                            'cornflowerblue',
                                            'cornsilk',
                                            'crimson',
                                            'cyan',
                                            'darkblue',
                                            'darkcyan',
                                            'darkgoldenrod',
                                            'darkgray',
                                            'darkgreen',
                                            'darkgrey',
                                            'darkkhaki',
                                            'darkmagenta',
                                            'darkolivegreen',
                                            'darkorange',
                                            'darkorchid',
                                            'darkred',
                                            'darksalmon',
                                            'darkseagreen',
                                            'darkslateblue',
                                            'darkslategray',
                                            'darkslategrey',
                                            'darkturquoise',
                                            'darkviolet',
                                            'deeppink',
                                            'deepskyblue',
                                            'dimgray',
                                            'dimgrey',
                                            'dodgerblue',
                                            'firebrick',
                                            'floralwhite',
                                            'forestgreen',
                                            'fuchsia',
                                            'gainsboro',
                                            'ghostwhite',
                                            'gold',
                                            'goldenrod',
                                            'gray',
                                            'green',
                                            'greenyellow',
                                            'grey',
                                            'honeydew',
                                            'hotpink',
                                            'indianred',
                                            'indigo',
                                            'ivory',
                                            'khaki',
                                            'lavender',
                                            'lavenderblush',
                                            'lawngreen',
                                            'lemonchiffon',
                                            'lightblue',
                                            'lightcoral',
                                            'lightcyan',
                                            'lightgoldenrodyellow',
                                            'lightgray',
                                            'lightgreen',
                                            'lightgrey',
                                            'lightpink',
                                            'lightsalmon',
                                            'lightseagreen',
                                            'lightskyblue',
                                            'lightslategray',
                                            'lightslategrey',
                                            'lightsteelblue',
                                            'lightyellow',
                                            'lime',
                                            'limegreen',
                                            'linen',
                                            'magenta',
                                            'maroon',
                                            'mediumaquamarine',
                                            'mediumblue',
                                            'mediumorchid',
                                            'mediumpurple',
                                            'mediumseagreen',
                                            'mediumslateblue',
                                            'mediumspringgreen',
                                            'mediumturquoise',
                                            'mediumvioletred',
                                            'midnightblue',
                                            'mintcream',
                                            'mistyrose',
                                            'moccasin',
                                            'navajowhite',
                                            'navy',
                                            'navyblue',
                                            'oldlace',
                                            'olive',
                                            'olivedrab',
                                            'orange',
                                            'orangered',
                                            'orchid',
                                            'palegoldenrod',
                                            'palegreen',
                                            'paleturquoise',
                                            'palevioletred',
                                            'papayawhip',
                                            'peachpuff',
                                            'peru',
                                            'pink',
                                            'plum',
                                            'powderblue',
                                            'purple',
                                            'red',
                                            'rosybrown',
                                            'royalblue',
                                            'saddlebrown',
                                            'salmon',
                                            'sandybrown',
                                            'seagreen',
                                            'seashell',
                                            'sienna',
                                            'silver',
                                            'skyblue',
                                            'slateblue',
                                            'slategray',
                                            'slategrey',
                                            'snow',
                                            'springgreen',
                                            'steelblue',
                                            'tan',
                                            'teal',
                                            'thistle',
                                            'tomato',
                                            'turquoise',
                                            'violet',
                                            'wheat',
                                            'white',
                                            'whitesmoke',
                                            'yellow',
                                            'yellowgreen',
                                        ],
                                    ],
                                ],
                                'name'        => 'Color name',
                                'description' => 'A human-readable color name.',
                            ],
                            'hs_color'            => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'example'     => '[300, 70]',
                                'selector'    => [
                                    'object' => null,
                                ],
                                'name'        => 'Hue/Sat color',
                                'description' => 'Color in hue/sat format. A list of two integers. Hue is 0-360 and Sat is 0-100.',
                            ],
                            'xy_color'            => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'example'     => '[0.52, 0.43]',
                                'selector'    => [
                                    'object' => null,
                                ],
                                'name'        => 'XY-color',
                                'description' => 'Color in XY-format. A list of two decimal numbers between 0 and 1.',
                            ],
                            'color_temp'          => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'color_temp',
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'selector'    => [
                                    'color_temp' => [
                                        'unit' => 'mired',
                                        'min'  => 153,
                                        'max'  => 500,
                                    ],
                                ],
                                'name'        => 'Color temperature',
                                'description' => 'Color temperature in mireds.',
                            ],
                            'kelvin'              => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'color_temp',
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'color_temp' => [
                                        'unit' => 'kelvin',
                                        'min'  => 2000,
                                        'max'  => 6500,
                                    ],
                                ],
                                'name'        => 'Color temperature',
                                'description' => 'Color temperature in Kelvin.',
                            ],
                            'brightness'          => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'brightness',
                                            'color_temp',
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'number' => [
                                        'min' => 0,
                                        'max' => 255,
                                    ],
                                ],
                                'name'        => 'Brightness value',
                                'description' => 'Number indicating brightness, where 0 turns the light off, 1 is the minimum brightness, and 255 is the maximum brightness.',
                            ],
                            'brightness_pct'      => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'brightness',
                                            'color_temp',
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'selector'    => [
                                    'number' => [
                                        'min'                 => 0,
                                        'max'                 => 100,
                                        'unit_of_measurement' => '%',
                                    ],
                                ],
                                'name'        => 'Brightness',
                                'description' => 'Number indicating the percentage of full brightness, where 0 turns the light off, 1 is the minimum brightness, and 100 is the maximum brightness.',
                            ],
                            'brightness_step'     => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'brightness',
                                            'color_temp',
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'number' => [
                                        'min' => -225,
                                        'max' => 255,
                                    ],
                                ],
                                'name'        => 'Brightness step value',
                                'description' => 'Change brightness by an amount.',
                            ],
                            'brightness_step_pct' => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'brightness',
                                            'color_temp',
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'selector'    => [
                                    'number' => [
                                        'min'                 => -100,
                                        'max'                 => 100,
                                        'unit_of_measurement' => '%',
                                    ],
                                ],
                                'name'        => 'Brightness step',
                                'description' => 'Change brightness by a percentage.',
                            ],
                            'white'               => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'white',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'constant' => [
                                        'value' => true,
                                        'label' => 'Enabled',
                                    ],
                                ],
                                'name'        => 'White',
                                'description' => 'Set the light to white mode.',
                            ],
                            'profile'             => [
                                'advanced'    => true,
                                'example'     => 'relax',
                                'selector'    => [
                                    'text' => null,
                                ],
                                'name'        => 'Profile',
                                'description' => 'Name of a light profile to use.',
                            ],
                            'flash'               => [
                                'filter'      => [
                                    'supported_features' => [
                                        8,
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'select' => [
                                        'options' => [
                                            [
                                                'label' => 'Long',
                                                'value' => 'long',
                                            ],
                                            [
                                                'label' => 'Short',
                                                'value' => 'short',
                                            ],
                                        ],
                                    ],
                                ],
                                'name'        => 'Flash',
                                'description' => 'Tell light to flash, can be either value short or long.',
                            ],
                            'effect'              => [
                                'filter'      => [
                                    'supported_features' => [
                                        4,
                                    ],
                                ],
                                'selector'    => [
                                    'text' => null,
                                ],
                                'name'        => 'Effect',
                                'description' => 'Light effect.',
                            ],
                        ],
                        'target'      => [
                            'entity' => [
                                [
                                    'domain' => [
                                        'light',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'turn_off' => [
                        'name'        => 'Turn off',
                        'description' => 'Turn off one or more lights.',
                        'fields'      => [
                            'transition' => [
                                'filter'      => [
                                    'supported_features' => [
                                        32,
                                    ],
                                ],
                                'selector'    => [
                                    'number' => [
                                        'min'                 => 0,
                                        'max'                 => 300,
                                        'unit_of_measurement' => 'seconds',
                                    ],
                                ],
                                'name'        => 'Transition',
                                'description' => 'Duration it takes to get to next state.',
                            ],
                            'flash'      => [
                                'filter'      => [
                                    'supported_features' => [
                                        8,
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'select' => [
                                        'options' => [
                                            [
                                                'label' => 'Long',
                                                'value' => 'long',
                                            ],
                                            1 => [
                                                'label' => 'Short',
                                                'value' => 'short',
                                            ],
                                        ],
                                    ],
                                ],
                                'name'        => 'Flash',
                                'description' => 'Tell light to flash, can be either value short or long.',
                            ],
                        ],
                        'target'      => [
                            'entity' => [
                                [
                                    'domain' => [
                                        'light',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'toggle'   => [
                        'name'        => 'Toggle',
                        'description' => 'Toggles one or more lights, from on to off, or, off to on, based on their current state.',
                        'fields'      => [
                            'transition'     => [
                                'filter'      => [
                                    'supported_features' => [
                                        32,
                                    ],
                                ],
                                'selector'    => [
                                    'number' => [
                                        'min'                 => 0,
                                        'max'                 => 300,
                                        'unit_of_measurement' => 'seconds',
                                    ],
                                ],
                                'name'        => 'Transition',
                                'description' => 'Duration it takes to get to next state.',
                            ],
                            'rgb_color'      => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'example'     => '[255, 100, 100]',
                                'selector'    => [
                                    'color_rgb' => null,
                                ],
                                'name'        => 'Color',
                                'description' => 'The color in RGB format. A list of three integers between 0 and 255 representing the values of red, green, and blue.',
                            ],
                            'color_name'     => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'select' => [
                                        'translation_key' => 'color_name',
                                        'options'         => [
                                            'homeassistant',
                                            'aliceblue',
                                            'antiquewhite',
                                            'aqua',
                                            'aquamarine',
                                            'azure',
                                            'beige',
                                            'bisque',
                                            'blanchedalmond',
                                            'blue',
                                            'blueviolet',
                                            'brown',
                                            'burlywood',
                                            'cadetblue',
                                            'chartreuse',
                                            'chocolate',
                                            'coral',
                                            'cornflowerblue',
                                            'cornsilk',
                                            'crimson',
                                            'cyan',
                                            'darkblue',
                                            'darkcyan',
                                            'darkgoldenrod',
                                            'darkgray',
                                            'darkgreen',
                                            'darkgrey',
                                            'darkkhaki',
                                            'darkmagenta',
                                            'darkolivegreen',
                                            'darkorange',
                                            'darkorchid',
                                            'darkred',
                                            'darksalmon',
                                            'darkseagreen',
                                            'darkslateblue',
                                            'darkslategray',
                                            'darkslategrey',
                                            'darkturquoise',
                                            'darkviolet',
                                            'deeppink',
                                            'deepskyblue',
                                            'dimgray',
                                            'dimgrey',
                                            'dodgerblue',
                                            'firebrick',
                                            'floralwhite',
                                            'forestgreen',
                                            'fuchsia',
                                            'gainsboro',
                                            'ghostwhite',
                                            'gold',
                                            'goldenrod',
                                            'gray',
                                            'green',
                                            'greenyellow',
                                            'grey',
                                            'honeydew',
                                            'hotpink',
                                            'indianred',
                                            'indigo',
                                            'ivory',
                                            'khaki',
                                            'lavender',
                                            'lavenderblush',
                                            'lawngreen',
                                            'lemonchiffon',
                                            'lightblue',
                                            'lightcoral',
                                            'lightcyan',
                                            'lightgoldenrodyellow',
                                            'lightgray',
                                            'lightgreen',
                                            'lightgrey',
                                            'lightpink',
                                            'lightsalmon',
                                            'lightseagreen',
                                            'lightskyblue',
                                            'lightslategray',
                                            'lightslategrey',
                                            'lightsteelblue',
                                            'lightyellow',
                                            'lime',
                                            'limegreen',
                                            'linen',
                                            'magenta',
                                            'maroon',
                                            'mediumaquamarine',
                                            'mediumblue',
                                            'mediumorchid',
                                            'mediumpurple',
                                            'mediumseagreen',
                                            'mediumslateblue',
                                            'mediumspringgreen',
                                            'mediumturquoise',
                                            'mediumvioletred',
                                            'midnightblue',
                                            'mintcream',
                                            'mistyrose',
                                            'moccasin',
                                            'navajowhite',
                                            'navy',
                                            'navyblue',
                                            'oldlace',
                                            'olive',
                                            'olivedrab',
                                            'orange',
                                            'orangered',
                                            'orchid',
                                            'palegoldenrod',
                                            'palegreen',
                                            'paleturquoise',
                                            'palevioletred',
                                            'papayawhip',
                                            'peachpuff',
                                            'peru',
                                            'pink',
                                            'plum',
                                            'powderblue',
                                            'purple',
                                            'red',
                                            'rosybrown',
                                            'royalblue',
                                            'saddlebrown',
                                            'salmon',
                                            'sandybrown',
                                            'seagreen',
                                            'seashell',
                                            'sienna',
                                            'silver',
                                            'skyblue',
                                            'slateblue',
                                            'slategray',
                                            'slategrey',
                                            'snow',
                                            'springgreen',
                                            'steelblue',
                                            'tan',
                                            'teal',
                                            'thistle',
                                            'tomato',
                                            'turquoise',
                                            'violet',
                                            'wheat',
                                            'white',
                                            'whitesmoke',
                                            'yellow',
                                            'yellowgreen',
                                        ],
                                    ],
                                ],
                                'name'        => 'Color name',
                                'description' => 'A human-readable color name.',
                            ],
                            'hs_color'       => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'example'     => '[300, 70]',
                                'selector'    => [
                                    'object' => null,
                                ],
                                'name'        => 'Hue/Sat color',
                                'description' => 'Color in hue/sat format. A list of two integers. Hue is 0-360 and Sat is 0-100.',
                            ],
                            'xy_color'       => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'example'     => '[0.52, 0.43]',
                                'selector'    => [
                                    'object' => null,
                                ],
                                'name'        => 'XY-color',
                                'description' => 'Color in XY-format. A list of two decimal numbers between 0 and 1.',
                            ],
                            'color_temp'     => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'color_temp',
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'color_temp' => null,
                                ],
                                'name'        => 'Color temperature',
                                'description' => 'Color temperature in mireds.',
                            ],
                            'kelvin'         => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'color_temp',
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'color_temp' => [
                                        'unit' => 'kelvin',
                                        'min'  => 2000,
                                        'max'  => 6500,
                                    ],
                                ],
                                'name'        => 'Color temperature',
                                'description' => 'Color temperature in Kelvin.',
                            ],
                            'brightness'     => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'brightness',
                                            'color_temp',
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'number' => [
                                        'min' => 0,
                                        'max' => 255,
                                    ],
                                ],
                                'name'        => 'Brightness value',
                                'description' => 'Number indicating brightness, where 0 turns the light off, 1 is the minimum brightness, and 255 is the maximum brightness.',
                            ],
                            'brightness_pct' => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'brightness',
                                            'color_temp',
                                            'hs',
                                            'xy',
                                            'rgb',
                                            'rgbw',
                                            'rgbww',
                                        ],
                                    ],
                                ],
                                'selector'    => [
                                    'number' => [
                                        'min'                 => 0,
                                        'max'                 => 100,
                                        'unit_of_measurement' => '%',
                                    ],
                                ],
                                'name'        => 'Brightness',
                                'description' => 'Number indicating the percentage of full brightness, where 0 turns the light off, 1 is the minimum brightness, and 100 is the maximum brightness.',
                            ],
                            'white'          => [
                                'filter'      => [
                                    'attribute' => [
                                        'supported_color_modes' => [
                                            'white',
                                        ],
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'constant' => [
                                        'value' => true,
                                        'label' => 'Enabled',
                                    ],
                                ],
                                'name'        => 'White',
                                'description' => 'Set the light to white mode.',
                            ],
                            'profile'        => [
                                'advanced'    => true,
                                'example'     => 'relax',
                                'selector'    => [
                                    'text' => null,
                                ],
                                'name'        => 'Profile',
                                'description' => 'Name of a light profile to use.',
                            ],
                            'flash'          => [
                                'filter'      => [
                                    'supported_features' => [
                                        8,
                                    ],
                                ],
                                'advanced'    => true,
                                'selector'    => [
                                    'select' => [
                                        'options' => [
                                            [
                                                'label' => 'Long',
                                                'value' => 'long',
                                            ],
                                            [
                                                'label' => 'Short',
                                                'value' => 'short',
                                            ],
                                        ],
                                    ],
                                ],
                                'name'        => 'Flash',
                                'description' => 'Tell light to flash, can be either value short or long.',
                            ],
                            'effect'         => [
                                'filter'      => [
                                    'supported_features' => [
                                        4,
                                    ],
                                ],
                                'selector'    => [
                                    'text' => null,
                                ],
                                'name'        => 'Effect',
                                'description' => 'Light effect.',
                            ],
                        ],
                        'target'      => [
                            'entity' => [
                                [
                                    'domain' => [
                                        'light',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'domain'   => 'adguard',
                'services' => [
                    'add_url'     => [
                        'name'        => 'Add URL',
                        'description' => 'Add a new filter subscription to AdGuard Home.',
                        'fields'      => [
                            'name' => [
                                'required'    => true,
                                'example'     => 'Example',
                                'selector'    => [
                                    'text' => null,
                                ],
                                'name'        => 'Name',
                                'description' => 'The name of the filter subscription.',
                            ],
                            'url'  => [
                                'required'    => true,
                                'example'     => 'https://www.example.com/filter/1.txt',
                                'selector'    => [
                                    'text' => null,
                                ],
                                'name'        => 'URL',
                                'description' => 'The filter URL to subscribe to, containing the filter rules.',
                            ],
                        ],
                    ],
                    'remove_url'  => [
                        'name'        => 'Remove URL',
                        'description' => 'Removes a filter subscription from AdGuard Home.',
                        'fields'      => [
                            'url' => [
                                'required'    => true,
                                'example'     => 'https://www.example.com/filter/1.txt',
                                'selector'    => [
                                    'text' => null,
                                ],
                                'name'        => 'URL',
                                'description' => 'The filter subscription URL to remove.',
                            ],
                        ],
                    ],
                    'enable_url'  => [
                        'name'        => 'Enable URL',
                        'description' => 'Enables a filter subscription in AdGuard Home.',
                        'fields'      => [
                            'url' => [
                                'required'    => true,
                                'example'     => 'https://www.example.com/filter/1.txt',
                                'selector'    => [
                                    'text' => null,
                                ],
                                'name'        => 'URL',
                                'description' => 'The filter subscription URL to enable.',
                            ],
                        ],
                    ],
                    'disable_url' => [
                        'name'        => 'Disable URL',
                        'description' => 'Disables a filter subscription in AdGuard Home.',
                        'fields'      => [
                            'url' => [
                                'required'    => true,
                                'example'     => 'https://www.example.com/filter/1.txt',
                                'selector'    => [
                                    'text' => null,
                                ],
                                'name'        => 'URL',
                                'description' => 'The filter subscription URL to disable.',
                            ],
                        ],
                    ],
                    'refresh'     => [
                        'name'        => 'Refresh',
                        'description' => 'Refresh all filter subscriptions in AdGuard Home.',
                        'fields'      => [
                            'force' => [
                                'default'     => false,
                                'selector'    => [
                                    'boolean' => null,
                                ],
                                'name'        => 'Force',
                                'description' => 'Force update (bypasses AdGuard Home throttling). "true" to force, or "false" to omit for a regular refresh.',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function getHistoryResponse(): array
    {
        return [
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
        ];
    }

    public static function getLogbookResponse(): array
    {
        return [
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
        ];
    }

    public static function getStatesResponse(): array
    {
        return [
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
        ];
    }

    public static function getStateResponse(): array
    {
        return [
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
        ];
    }

    public static function getErrorLogResponse(): string
    {
        return "2024-02-14 22:29:10.441 WARNING (SyncWorker_0) [homeassistant.loader] We found a custom integration browser_mod which has not been tested by Home Assistant. This component might cause stability problems, be sure to disable it if you experience issues with Home Assistant\n
2024-02-14 22:29:10.447 WARNING (SyncWorker_0) [homeassistant.loader] We found a custom integration hacs which has not been tested by Home Assistant. This component might cause stability problems, be sure to disable it if you experience issues with Home Assistant\n";
    }

    public static function getCalendarsResponse(): array
    {
        return [
            [
                'name'      => 'Birthdays',
                'entity_id' => 'calendar.birthdays',
            ],
        ];
    }

    public static function getCalendarEventsResponse(): array
    {
        return [
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
        ];
    }
}
