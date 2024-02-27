<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Webhook;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class WebhookSuccess extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'application/octet-stream',
            '',
            'OK'
        );
    }
}
