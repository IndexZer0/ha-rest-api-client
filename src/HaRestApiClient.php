<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient;

use DateTimeInterface;
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
        private string           $bearerToken,
        private HaInstanceConfig $config = new HaInstanceConfig(),
        private ?HandlerStack    $handlerStack = null
    )
    {
        $this->initGuzzleClient();
    }

    protected function initGuzzleClient(): void
    {
        $this->guzzleClient = new GuzzleClient([
            'base_uri' => $this->config->getUrL(),
            'headers'  => [
                'Authorization' => "Bearer {$this->bearerToken}",
                'Content-Type'  => 'application/json',
            ],
            'handler'  => $this->handlerStack
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

    public function events(): array
    {
        return $this->handleRequest(function () {
            return $this->guzzleClient->get('events');
        });
    }

    public function services(): array
    {
        return $this->handleRequest(function () {
            return $this->guzzleClient->get("services");
        });
    }

    public function history(
        array              $entityIds,
        ?DateTimeInterface $startTime = null,
        ?DateTimeInterface $endTime = null,
        bool               $minimalResponse = false,
        bool               $noAttributes = false,
        bool               $significantChangesOnly = false,
    ): array
    {
        if (count($entityIds) < 1) {
            throw new HaException('Provide at least one entity id.');
        }

        foreach ($entityIds as $entityId) {
            if (!is_string($entityId)) {
                throw new HaException('Entity id must be string.');
            }
        }

        $path = "history/period";

        $dateFormat = 'Y-m-d\Th:m:sP';

        if ($startTime !== null) {
            $path .= '/' . $startTime->format($dateFormat);
        }

        $booleanQueryParams = array_filter([
            'minimal_response'         => $minimalResponse,
            'no_attributes'            => $noAttributes,
            'significant_changes_only' => $significantChangesOnly,
        ], function ($value) {
            return $value === true;
        });

        $queryParams = [
            'filter_entity_id' => join(',', $entityIds),
            ...$booleanQueryParams
        ];

        if ($endTime !== null) {
            $queryParams['end_time'] = $endTime->format($dateFormat);
        }

        return $this->handleRequest(function () use ($path, $queryParams) {
            return $this->guzzleClient->get($path, [
                'query' => $queryParams
            ]);
        });
    }

    public function logbook(
        ?string            $entityId = null,
        ?DateTimeInterface $startTime = null,
        ?DateTimeInterface $endTime = null,
    ): array
    {
        $path = "logbook";

        $dateFormat = 'Y-m-d\Th:m:sP';

        if ($startTime !== null) {
            $path .= '/' . $startTime->format($dateFormat);
        }

        $queryParams = [];

        if ($entityId !== null) {
            $queryParams['entity'] = $entityId;
        }

        if ($endTime !== null) {
            $queryParams['end_time'] = $endTime->format($dateFormat);
        }

        return $this->handleRequest(function () use ($path, $queryParams) {
            return $this->guzzleClient->get($path, [
                'query' => $queryParams
            ]);
        });
    }

    public function states(): array
    {
        return $this->handleRequest(function () {
            return $this->guzzleClient->get('states');
        });
    }

    public function state(string $entityId): array
    {
        return $this->handleRequest(function () use ($entityId) {
            return $this->guzzleClient->get("states/{$entityId}");
        });
    }

    public function errorLog(): array
    {
        return $this->handleRequest(function () {
            return $this->guzzleClient->get('error_log');
        });
    }

    // TODO
    /*public function camera(string $entityId): array
    {
        // TODO get param (time).

        return $this->handleRequest(function () use ($entityId) {
            return $this->guzzleClient->get("camera_proxy/{$entityId}");
        });
    }*/

    public function calendars(): array
    {
        return $this->handleRequest(function () {
            return $this->guzzleClient->get('calendars');
        });
    }

    public function calendarEvents(
        string            $entityId,
        DateTimeInterface $start,
        DateTimeInterface $end,
    ): array
    {
        $dateFormat = 'Y-m-d\Th:m:sP';

        $queryParams = [
            'start' => $start->format($dateFormat),
            'end'   => $end->format($dateFormat)
        ];

        return $this->handleRequest(function () use ($entityId, $queryParams) {
            return $this->guzzleClient->get("calendars/{$entityId}", [
                'query' => $queryParams
            ]);
        });
    }

    // TODO
    /*public function updateState(
        string $entityId,
        string $state,
        ?array $attributes
    ): array {

        $data = ['state' => $state,];

        if ($attributes !== null) {
            $data['attributes'] = $attributes;
        }

        return $this->handleRequest(function () use ($entityId, $data) {
            return $this->guzzleClient->post("states/{$entityId}", [
                RequestOptions::JSON => $data,
            ]);
        });
    }*/

    // TODO
    /*public function fireEvent(
        string $eventType,
        ?array $eventData
    ): array {
        return $this->handleRequest(function () use ($eventType, $eventData) {
            return $this->guzzleClient->post("events/{$eventType}", [
                RequestOptions::JSON => $eventData,
            ]);
        });
    }*/

    public function callService(Domain $domain, Service $service, array $data): array
    {
        $url = 'services/' . $domain->value . '/' . $service->value;

        return $this->handleRequest(function () use ($url, $data) {
            return $this->guzzleClient->post($url, [
                RequestOptions::JSON => $data,
            ]);
        });
    }

    // TODO
    /*public function renderTemplate(string $template): array
    {
        return $this->handleRequest(function () use ($template) {
            return $this->guzzleClient->post('template', [
                RequestOptions::JSON => ['template' => $template],
            ]);
        });
    }*/

    // TODO
    /*public function checkConfig(): array
    {
        return $this->handleRequest(function () {
            return $this->guzzleClient->post('config/core/check_config');
        });
    }*/

    // TODO
    /*public function handleIntent(array $data): array
    {
        return $this->handleRequest(function () use ($data) {
            return $this->guzzleClient->post('intent/handle', [
                 RequestOptions::JSON => $data,
            ]);
        });
    }*/

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

        $contentType = 'application/json';
        if ($response->hasHeader('Content-Type')) {
            $contentType = $response->getHeader('Content-Type')[0];
        }

        if ($contentType === 'application/json') {
            try {
                $json = json_decode($responseBodyContent, true, flags: JSON_THROW_ON_ERROR);
                if (!is_array($json)) {
                    return [$json];
                }
                return $json;
            } catch (JsonException $je) {
                throw new HaException('Invalid JSON Response.', previous: $je);
            }
        }

        return [
            'response' => $responseBodyContent
        ];
    }
}
