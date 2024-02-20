<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\RenderTemplate;

use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;

readonly class RenderTemplateFailBadRequest extends ResponseDefinition
{
    public function __construct()
    {
        parent::__construct(
            400,
            'application/json',
            json_encode([
                "message" => "Error rendering template: UndefinedError: 'statess' is undefined"
            ]),
            'Bad Request'
        );
    }
}
