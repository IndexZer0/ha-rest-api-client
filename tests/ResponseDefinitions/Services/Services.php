<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Services;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class Services extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/json',
            json_encode([
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
            ]),
            'OK'
        );
    }
}
