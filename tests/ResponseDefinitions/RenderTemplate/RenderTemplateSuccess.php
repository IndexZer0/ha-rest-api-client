<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\RenderTemplate;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class RenderTemplateSuccess extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            200,
            'text/plain; charset=utf-8',
            'The bedroom ceiling light is off.',
            'OK'
        );
    }
}
