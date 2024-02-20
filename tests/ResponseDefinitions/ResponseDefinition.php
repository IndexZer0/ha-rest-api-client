<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\ResponseDefinitions;

use GuzzleHttp\Psr7\Response;

readonly class ResponseDefinition
{
    public function __construct(
        public int $statusCode,
        public string $contentType,
        public string $bodyContent,
        public string $reasonPhrase,
    ) {
    }

    public function getResponse(): Response
    {
        return new Response(
            status: $this->statusCode,
            headers: ['Content-Type' => $this->contentType],
            body: $this->bodyContent,
            reason: $this->reasonPhrase
        );
    }

    public function getBodyAsArray(): array
    {
        return json_decode($this->bodyContent, true);
    }
}
