<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient;

use GuzzleHttp\Client as GuzzleClient;
use Throwable;

class HaRestApiClient
{
    public readonly GuzzleClient $guzzleClient;

    public function __construct(
        private HaInstanceConfig $config,
        private string $bearerToken
    ) {
        $this->initGuzzleClient();
    }

    private function initGuzzleClient(): void
    {
        $this->guzzleClient = new GuzzleClient([
            'base_uri' => $this->config->getUrL(),
            'headers' => [
                'Authorization' => "Bearer $this->bearerToken",
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function status(): array
    {
        try {
            $response = $this->guzzleClient->get('');
        } catch (Throwable $t) {
            throw new HaException(previous: $t);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
