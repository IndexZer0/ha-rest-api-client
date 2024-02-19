<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
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
                'Authorization' => "Bearer {$this->bearerToken}",
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

    public function config(): array
    {
        try {
            $response = $this->guzzleClient->get('config');
        } catch (Throwable $t) {
            throw new HaException(previous: $t);
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function callService(Domain $domain, Service $service, array $data): array
    {
        $url = 'services/' . $domain->value . '/' . $service->value;

        try {
            $response = $this->guzzleClient->post($url, [
                RequestOptions::JSON => $data,
            ]);
        } catch (ClientException $ce) {
            throw $ce;
        } catch (Throwable $t) {
            throw new HaException('Unknown error', previous: $t);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
