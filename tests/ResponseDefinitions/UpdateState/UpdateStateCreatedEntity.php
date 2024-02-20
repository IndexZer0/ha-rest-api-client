<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\UpdateState;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class UpdateStateCreatedEntity extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            201,
            'application/json',
            json_encode([
                'entity_id' => 'sensor.test_api',
                'state' => 'on',
                'attributes' => [
                    'attr1' => 1,
                    'attr2' => [
                        'attr3' => 'three',
                    ],
                ],
                'last_changed' => '2024-02-20T14:06:53.390121+00:00',
                'last_updated' => '2024-02-20T14:06:53.390121+00:00',
                'context' => [
                    'id' => 'context-id',
                    'parent_id' => null,
                    'user_id' => 'context-user_id',
                ],
            ]),
            'Created'
        );
    }
}
