<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient;

use DateTimeInterface;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Message\Authentication\Bearer;
use IndexZer0\HaRestApiClient\Exception\HaException;
use IndexZer0\HaRestApiClient\Exception\InvalidArgumentException;
use IndexZer0\HaRestApiClient\HttpClient\Builder;
use IndexZer0\HaRestApiClient\Traits\HandlesRequests;
use SensitiveParameter;
use Throwable;

class HaRestApiClient
{
    use HandlesRequests;

    private static string $dateFormat = 'Y-m-d\Th:m:sP';

    public function __construct(
        #[SensitiveParameter]
        private string          $bearerToken,
        private string          $baseUri,
        public readonly Builder $httpClientBuilder = new Builder(),
    ) {
        try {
            $this->httpClientBuilder->addPlugin(new AuthenticationPlugin(new Bearer($this->bearerToken)));
            $this->httpClientBuilder->addPlugin(new HeaderDefaultsPlugin([
                'Content-Type' => 'application/json',
            ]));
            $this->httpClientBuilder->addPlugin(new BaseUriPlugin(
                $this->httpClientBuilder->getUriFactory()->createUri($this->baseUri),
                [
                    // Always replace the host, even if this one is provided on the sent request. Available for AddHostPlugin.
                    'replace' => true,
                ]
            ));
        } catch (Throwable $t) {
            throw new HaException($t->getMessage(), $t->getCode(), $t);
        }
    }

    /*
     * Returns a message if the API is up and running.
     */
    public function status(): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('GET', '/')
        );
    }

    /*
     * Returns the current configuration.
     */
    public function config(): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('GET', '/config')
        );
    }

    /*
     * Returns an array of event objects.
     * Each event object contains event name and listener count.
     */
    public function events(): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('GET', '/events')
        );
    }

    /**
     * Returns an array of service objects.
     * Each object contains the domain and which services it contains.
     */
    public function services(): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('GET', '/services')
        );
    }

    /*
     * Returns an array of state changes in the past.
     * Each object contains further details for the entities.
     */
    public function history(
        array              $entityIds,
        ?DateTimeInterface $startTime = null,
        ?DateTimeInterface $endTime = null,
        bool               $minimalResponse = false,
        bool               $noAttributes = false,
        bool               $significantChangesOnly = false,
    ): array {
        if (count($entityIds) < 1) {
            throw new InvalidArgumentException('Provide at least one entity id.');
        }

        foreach ($entityIds as $entityId) {
            if (!is_string($entityId)) {
                throw new InvalidArgumentException('Entity id must be string.');
            }
        }

        $path = "/history/period";

        if ($startTime !== null) {
            $path .= '/' . $startTime->format(self::$dateFormat);
        }

        $booleanQueryParams = array_filter([
            'minimal_response'         => $minimalResponse,
            'no_attributes'            => $noAttributes,
            'significant_changes_only' => $significantChangesOnly,
        ], fn ($value) => $value);

        $queryParams = [
            'filter_entity_id' => join(',', $entityIds),
            ...$booleanQueryParams
        ];

        if ($endTime !== null) {
            $queryParams['end_time'] = $endTime->format(self::$dateFormat);
        }

        return $this->handleRequest(
            $this->createRequestWithQuery('GET', $path, $queryParams)
        );
    }

    /*
     * Returns an array of logbook entries.
     */
    public function logbook(
        ?string            $entityId = null,
        ?DateTimeInterface $startTime = null,
        ?DateTimeInterface $endTime = null,
    ): array {
        $path = "/logbook";

        if ($startTime !== null) {
            $path .= '/' . $startTime->format(self::$dateFormat);
        }

        $queryParams = [];

        if ($entityId !== null) {
            $queryParams['entity'] = $entityId;
        }

        if ($endTime !== null) {
            $queryParams['end_time'] = $endTime->format(self::$dateFormat);
        }

        return $this->handleRequest(
            $this->createRequestWithQuery('GET', $path, $queryParams)
        );
    }

    /*
     * Returns an array of state objects.
     * Each state has the following attributes: entity_id, state, last_changed and attributes.
     */
    public function states(): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('GET', '/states')
        );
    }

    /*
     * Returns a state object for specified entity_id.
     * Throws exception if entity not found.
     */
    public function state(string $entityId): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('GET', "/states/{$entityId}")
        );
    }

    /*
     * Retrieve all errors logged during the current session of Home Assistant.
     */
    public function errorLog(): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('GET', '/error_log')
        );
    }

    /*
     * Returns the data (image) from the specified camera entity_id.
     * // TODO implement
     */
    /*public function camera(string $entityId): array
    {
        throw new \Exception('Not implemented');

        // TODO get param (time).

        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('GET', "/camera_proxy/{$entityId}")
        );
    }*/

    /*
     * Returns the list of calendar entities.
     */
    public function calendars(): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('GET', '/calendars')
        );
    }

    /*
     * Returns the list of calendar events for the specified calendar entity_id between the start and end times (exclusive).
     */
    public function calendarEvents(
        string            $entityId,
        DateTimeInterface $start,
        DateTimeInterface $end,
    ): array {
        return $this->handleRequest(
            $this->createRequestWithQuery('GET', "/calendars/{$entityId}", [
                'start' => $start->format(self::$dateFormat),
                'end'   => $end->format(self::$dateFormat)
            ])
        );
    }

    /*
     * Updates or creates a state. You can create any state that you want, it does not have to be backed by an entity in Home Assistant.
     */
    public function updateState(
        string $entityId,
        string $state,
        ?array $attributes
    ): array {
        $data = ['state' => $state,];

        if ($attributes !== null) {
            $data['attributes'] = $attributes;
        }

        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('POST', "/states/{$entityId}")->withBody(
                $this->httpClientBuilder->getStreamFactory()->createStream(json_encode($data))
            )
        );
    }

    /*
     * Fires an event with event_type.
     * Please be mindful of the data structure as documented on the Data Science portal.
     * https://data.home-assistant.io/docs/events/#database-table
     */
    public function fireEvent(
        string $eventType,
        ?array $eventData = null
    ): array {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('POST', "/events/{$eventType}")->withBody(
                $this->httpClientBuilder->getStreamFactory()->createStream(json_encode($eventData))
            )
        );
    }

    /*
     * Calls a service within a specific domain. Will return when the service has been executed.
     */
    public function callService(string $domain, string $service, array $data): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('POST', "/services/{$domain}/{$service}")->withBody(
                $this->httpClientBuilder->getStreamFactory()->createStream(json_encode($data))
            )
        );
    }

    /*
     * Render a Home Assistant template.
     * See template docs for more information.
     * https://www.home-assistant.io/docs/configuration/templating
     */
    public function renderTemplate(string $template): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('POST', "/template")->withBody(
                $this->httpClientBuilder->getStreamFactory()->createStream(json_encode(['template' => $template]))
            )
        );
    }

    /*
     * Trigger a check of configuration.yaml.
     * Needs config integration enabled.
     */
    public function checkConfig(): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('POST', '/config/core/check_config')
        );
    }

    /*
     * Handle an intent.
     * You must add `intent:` to your configuration.yaml.
     */
    public function handleIntent(array $data): array
    {
        return $this->handleRequest(
            $this->httpClientBuilder->getRequestFactory()->createRequest('POST', '/intent/handle')->withBody(
                $this->httpClientBuilder->getStreamFactory()->createStream(json_encode($data))
            )
        );
    }
}
