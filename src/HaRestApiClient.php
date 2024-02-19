<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class HaRestApiClient
{
    public readonly GuzzleClient $guzzleClient;

    public function __construct(
        private HaInstanceConfig $config,
        private string $bearerToken,
        private ?HandlerStack $handlerStack = null
    ) {
        $this->initGuzzleClient();
    }

    protected function initGuzzleClient(): void
    {
        $this->guzzleClient = new GuzzleClient([
            'base_uri' => $this->config->getUrL(),
            'headers' => [
                'Authorization' => "Bearer {$this->bearerToken}",
                'Content-Type' => 'application/json',
            ],
            'handler' => $this->handlerStack
        ]);
    }

    public function status(): array
    {
        return $this->handleRequest(function () {
            return $this->guzzleClient->get('');
        });
    }

    public function config(): array
    {
        return $this->handleRequest(function () {
            return $this->guzzleClient->get('config');
        });
    }

    public function callService(Domain $domain, Service $service, array $data): array
    {
        $url = 'services/' . $domain->value . '/' . $service->value;

        return $this->handleRequest(function () use ($url, $data) {
            return $this->guzzleClient->post($url, [
                RequestOptions::JSON => $data,
            ]);
        });
    }

    /**
     * ---------------------------------------------------------------------------------
     * Helpers
     * ---------------------------------------------------------------------------------
     */

    private function handleRequest(callable $callable): array
    {
        try {
            /** @var ResponseInterface $response */
            $response = $callable();
        } catch (ClientException $ce) {
            throw new HaException($ce->getResponse()->getBody()->getContents(), previous: $ce);
        } catch (Throwable $t) {
            throw new HaException('Unknown Error.', previous: $t);
        }

        $responseBodyContent = $response->getBody()->getContents();

        try {
            return json_decode($responseBodyContent, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $je) {
            throw new HaException('Invalid JSON Response.', previous: $je);
        }
    }
}
