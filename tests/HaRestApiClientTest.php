<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests;

use DateTime;
use DateTimeInterface;
use Generator;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use IndexZer0\HaRestApiClient\Domain;
use IndexZer0\HaRestApiClient\HaException;
use IndexZer0\HaRestApiClient\HaInstanceConfig;
use IndexZer0\HaRestApiClient\HaRestApiClient;
use IndexZer0\HaRestApiClient\Service;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\GeneralHttp\Auth;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\CalendarEvents\CalendarEvents;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Calendars\Calendars;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\CallService\CallService;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\CheckConfig\CheckConfig;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Config\Config;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ErrorLog\ErrorLog;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Events\Events;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\GeneralHttp\BadRequest;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\GeneralHttp\ServerError;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\History\History;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Logbook\Logbook;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Misc\InvalidJson;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Misc\NullJson;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Services\Services;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\State\State;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\State\StateEntityNotFound;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\States\States;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Status\Status;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\FireEvent\FireEventHomeAssistantStart;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\FireEvent\FireEventHomeAssistantStop;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\FireEvent\FireEventScriptStarted;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\HandleIntent\HandleIntentFailServerError;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\HandleIntent\HandleIntentSuccess;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\RenderTemplate\RenderTemplateFailBadRequest;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\RenderTemplate\RenderTemplateSuccess;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\ResponseDefinition;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\UpdateState\UpdateStateCreatedEntity;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\UpdateState\UpdateStateUpdatedEntity;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HaRestApiClientTest extends TestCase
{
    private string $defaultBearerToken = 'bearerToken';

    private string $defaultBaseUri = 'http://localhost:8123/api/';

    #[Test]
    #[DataProvider('client_sends_bearer_token_provider')]
    public function client_sends_bearer_token(string $bearer_token): void
    {
        $responseDefinition = new Status();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $bearer_token,
            new HaInstanceConfig(),
            $handlerStack
        );

        $status = $client->status();

        $this->assertSame($responseDefinition->getBodyAsArray(), $status);

        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $bearer_token,
            $this->defaultBaseUri,
        );
    }

    public static function client_sends_bearer_token_provider(): Generator
    {
        yield 'bearer-1' => [
            'bearer_token' => 'bearer-1',
        ];

        yield 'bearer-2' => [
            'bearer_token' => 'bearer-2',
        ];
    }

    #[Test]
    #[DataProvider('client_uses_correct_instance_config_provider')]
    public function client_uses_correct_instance_config(
        HaInstanceConfig $ha_instance_config,
        string           $expected_url
    ): void
    {
        $responseDefinition = new Status();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            $ha_instance_config,
            $handlerStack
        );

        $status = $client->status();

        $this->assertSame($responseDefinition->getBodyAsArray(), $status);

        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $expected_url
        );
    }

    public static function client_uses_correct_instance_config_provider(): Generator
    {
        yield 'default' => [
            'ha_instance_config' => new HaInstanceConfig(),
            'expected_url'       => 'http://localhost:8123/api/',
        ];

        yield 'different' => [
            'ha_instance_config' => new HaInstanceConfig(
                'foreignhost',
                8124,
                '/api2/'
            ),
            'expected_url'       => 'http://foreignhost:8124/api2/',
        ];
    }

    #[Test]
    public function client_handles_unauthorized_response(): void
    {
        $responseDefinition = new Auth();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        try {
            $client->status();
            $this->fail();
        } catch (HaException $haException) {

        }

        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri,
        );
    }

    #[Test]
    public function client_can_get_status(): void
    {
        $responseDefinition = new Status();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            'bearerToken',
            new HaInstanceConfig(),
            $handlerStack
        );

        $status = $client->status();

        $this->assertSame($responseDefinition->getBodyAsArray(), $status);

        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri
        );
    }

    #[Test]
    public function client_can_get_config(): void
    {
        $responseDefinition = new Config();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $config = $client->config();

        $this->assertSame($responseDefinition->getBodyAsArray(), $config);

        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'config'
        );
    }

    #[Test]
    public function client_can_get_events(): void
    {
        $responseDefinition = new Events();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $events = $client->events();

        $this->assertSame($responseDefinition->getBodyAsArray(), $events);

        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'events'
        );
    }

    #[Test]
    public function client_can_get_services(): void
    {
        $responseDefinition = new Services();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $services = $client->services();

        $this->assertSame($responseDefinition->getBodyAsArray(), $services);

        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'services'
        );
    }

    #[Test]
    #[DataProvider('client_can_get_history_provider')]
    public function client_can_get_history(
        array              $entity_ids,
        bool               $minimal_response,
        bool               $no_attributes,
        bool               $significant_changes_only,
        bool               $expect_error,
        ?string            $expected_error_message = null,
        bool               $expect_request_sent,
        ?string            $expected_url = null,
        ?DateTimeInterface $start_time = null,
        ?DateTimeInterface $end_time = null,
    ): void
    {
        $responseDefinition = new History();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        try {
            $history = $client->history(
                $entity_ids,
                $start_time,
                $end_time,
                $minimal_response,
                $no_attributes,
                $significant_changes_only
            );
            if ($expect_error) {
                $this->fail('Should have failed.');
            }
        } catch (HaException $haException) {
            if (!$expect_error) {
                $this->fail('Should not have failed.');
            }
            $this->assertSame($expected_error_message, $haException->getMessage());
        }

        if ($expect_request_sent) {
            $this->assertCount(1, $historyContainer);
            $this->assertSame($responseDefinition->getBodyAsArray(), $history);
            /** @var Request $request */
            $request = $historyContainer[0]['request'];

            $this->assertSame('GET', $request->getMethod());

            $this->performCommonGuzzleRequestAssertions(
                $request,
                $this->defaultBearerToken,
                $this->defaultBaseUri . $expected_url
            );
        } else {
            $this->assertCount(0, $historyContainer);
        }
    }

    public static function client_can_get_history_provider(): Generator
    {
        yield 'no entity ids' => [
            'start_time'               => null,
            'entity_ids'               => [],
            'end_time'                 => null,
            'minimal_response'         => false,
            'no_attributes'            => false,
            'significant_changes_only' => false,
            'expect_error'             => true,
            'expected_error_message'   => 'Provide at least one entity id.',
            'expect_request_sent'      => false,
            'expected_url'             => null,
        ];

        yield 'invalid entity id type' => [
            'start_time'               => null,
            'entity_ids'               => [1],
            'end_time'                 => null,
            'minimal_response'         => false,
            'no_attributes'            => false,
            'significant_changes_only' => false,
            'expect_error'             => true,
            'expected_error_message'   => 'Entity id must be string.',
            'expect_request_sent'      => false,
            'expected_url'             => null,
        ];

        yield 'no start_time & valid entity id' => [
            'start_time'               => null,
            'entity_ids'               => ['light.bedroom_ceiling'],
            'end_time'                 => null,
            'minimal_response'         => false,
            'no_attributes'            => false,
            'significant_changes_only' => false,
            'expect_error'             => false,
            'expected_error_message'   => null,
            'expect_request_sent'      => true,
            'expected_url'             => 'history/period?filter_entity_id=light.bedroom_ceiling',
        ];

        yield 'start_time & valid entity id' => [
            'start_time'               => new DateTime('2024-02-19 11:02:54'),
            'entity_ids'               => ['light.bedroom_ceiling'],
            'end_time'                 => null,
            'minimal_response'         => false,
            'no_attributes'            => false,
            'significant_changes_only' => false,
            'expect_error'             => false,
            'expected_error_message'   => null,
            'expect_request_sent'      => true,
            'expected_url'             => 'history/period/2024-02-19T11:02:54+00:00?filter_entity_id=light.bedroom_ceiling',
        ];

        yield 'with partial query string' => [
            'start_time'               => new DateTime('2024-02-19 11:02:54'),
            'entity_ids'               => ['light.bedroom_ceiling'],
            'end_time'                 => new DateTime('2024-02-19 11:02:54'),
            'minimal_response'         => true,
            'no_attributes'            => false,
            'significant_changes_only' => false,
            'expect_error'             => false,
            'expected_error_message'   => null,
            'expect_request_sent'      => true,
            'expected_url'             => 'history/period/2024-02-19T11:02:54+00:00?filter_entity_id=light.bedroom_ceiling&minimal_response=1&end_time=2024-02-19T11%3A02%3A54%2B00%3A00',
        ];

        yield 'with full query string' => [
            'start_time'               => new DateTime('2024-02-19 11:02:54'),
            'entity_ids'               => ['light.bedroom_ceiling'],
            'end_time'                 => new DateTime('2024-02-19 11:02:54'),
            'minimal_response'         => true,
            'no_attributes'            => true,
            'significant_changes_only' => true,
            'expect_error'             => false,
            'expected_error_message'   => null,
            'expect_request_sent'      => true,
            'expected_url'             => 'history/period/2024-02-19T11:02:54+00:00?filter_entity_id=light.bedroom_ceiling&minimal_response=1&no_attributes=1&significant_changes_only=1&end_time=2024-02-19T11%3A02%3A54%2B00%3A00',
        ];
    }

    #[Test]
    #[DataProvider('client_can_get_logbook_provider')]
    public function client_can_get_logbook(
        string             $expected_url,
        ?DateTimeInterface $start_time = null,
        ?DateTimeInterface $end_time = null,
        ?string            $entity_id = null,
    ): void
    {
        $responseDefinition = new Logbook();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $logbook = $client->logbook(
            $entity_id,
            $start_time,
            $end_time,
        );

        $this->assertCount(1, $historyContainer);
        $this->assertSame($responseDefinition->getBodyAsArray(), $logbook);
        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . $expected_url
        );
    }

    public static function client_can_get_logbook_provider(): Generator
    {
        yield 'null params' => [
            'entity_id'    => null,
            'start_time'   => null,
            'end_time'     => null,
            'expected_url' => 'logbook',
        ];

        yield 'entity_id' => [
            'entity_id'    => 'light.bedroom_ceiling',
            'start_time'   => null,
            'end_time'     => null,
            'expected_url' => 'logbook?entity=light.bedroom_ceiling',
        ];

        yield 'start_time' => [
            'entity_id'    => null,
            'start_time'   => new DateTime('2024-02-19 11:02:54'),
            'end_time'     => null,
            'expected_url' => 'logbook/2024-02-19T11:02:54+00:00',
        ];

        yield 'end_time' => [
            'entity_id'    => null,
            'start_time'   => null,
            'end_time'     => new DateTime('2024-02-19 11:02:54'),
            'expected_url' => 'logbook?end_time=2024-02-19T11%3A02%3A54%2B00%3A00',
        ];

        yield 'all params' => [
            'entity_id'    => 'light.bedroom_ceiling',
            'start_time'   => new DateTime('2024-02-19 11:02:54'),
            'end_time'     => new DateTime('2024-02-19 11:02:54'),
            'expected_url' => 'logbook/2024-02-19T11:02:54+00:00?entity=light.bedroom_ceiling&end_time=2024-02-19T11%3A02%3A54%2B00%3A00',
        ];
    }

    #[Test]
    public function client_can_get_states(): void
    {
        $responseDefinition = new States();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $states = $client->states();

        $this->assertCount(1, $historyContainer);
        $this->assertSame($responseDefinition->getBodyAsArray(), $states);
        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'states'
        );
    }

    #[Test]
    #[DataProvider('client_can_get_state_provider')]
    public function client_can_get_state(
        ResponseDefinition $response_definition,
        bool               $expect_error,
        ?string            $expected_error_message = null,
    ): void
    {
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $response_definition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        try {
            $state = $client->state('light.bedroom_ceiling');
            if ($expect_error) {
                $this->fail('Should have failed.');
            }
            $this->assertSame($response_definition->getBodyAsArray(), $state);
        } catch (HaException $haException) {
            if (!$expect_error) {
                $this->fail('Should not have failed.');
            }
            $this->assertSame($expected_error_message, $haException->getMessage());
        }

        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . "states/light.bedroom_ceiling"
        );
    }

    public static function client_can_get_state_provider(): Generator
    {
        yield 'success' => [
            'response_definition'    => new State(),
            'expect_error'           => false,
            'expected_error_message' => null,
        ];

        yield 'error' => [
            'response_definition'    => $stateEntityNotFound = new StateEntityNotFound(),
            'expect_error'           => true,
            // TODO - handle this error message better.
            'expected_error_message' => $stateEntityNotFound->bodyContent,
        ];
    }

    #[Test]
    public function client_can_get_error_log(): void
    {
        $responseDefinition = new ErrorLog();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $errorLog = $client->errorLog();

        $this->assertCount(1, $historyContainer);
        $this->assertSame(['response' => $responseDefinition->bodyContent], $errorLog);
        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'error_log'
        );
    }

    #[Test]
    public function client_can_get_calendars(): void
    {
        $responseDefinition = new Calendars();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $calendars = $client->calendars();

        $this->assertCount(1, $historyContainer);
        $this->assertSame($responseDefinition->getBodyAsArray(), $calendars);
        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'calendars'
        );
    }

    #[Test]
    public function client_can_get_calendar_events(): void
    {
        $responseDefinition = new CalendarEvents();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $calendarEvents = $client->calendarEvents(
            'calendar.birthdays',
            new DateTime('2024-02-10 00:00:00'),
            new DateTime('2024-02-20 23:59:59'),
        );

        $this->assertCount(1, $historyContainer);
        $this->assertSame($responseDefinition->getBodyAsArray(), $calendarEvents);
        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'calendars/calendar.birthdays?start=2024-02-10T12%3A02%3A00%2B00%3A00&end=2024-02-20T11%3A02%3A59%2B00%3A00'
        );
    }

    #[Test]
    #[DataProvider('client_can_update_state_provider')]
    public function client_can_update_state(ResponseDefinition $response_definition): void
    {
        // Setup Handler stack.
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);
        $mockHandler = new MockHandler([
            $response_definition->getResponse()
        ]);
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        // Create client.
        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        // Call method.
        $payload = [
            'state'      => $state = 'on',
            'attributes' => $attributes = [
                'attr1' => 1,
                'attr2' => [
                    'attr3' => 'three'
                ],
            ]
        ];
        $updateState = $client->updateState(
            $entityId = 'sensor.test_api',
            $state,
            $attributes
        );

        // Assert client returns correct data.
        $this->assertSame($response_definition->getBodyAsArray(), $updateState);

        // Assert request sent correctly.
        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame($payload, json_decode($request->getBody()->getContents(), true));

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . "states/{$entityId}"
        );
    }

    public static function client_can_update_state_provider(): Generator
    {
        yield 'create' => [
            'response_definition' => new UpdateStateCreatedEntity(),
        ];

        yield 'update' => [
            'response_definition' => new UpdateStateUpdatedEntity(),
        ];
    }

    #[Test]
    #[DataProvider('client_can_fire_event_provider')]
    public function client_can_fire_event(
        ResponseDefinition $response_definition,
        string             $event_type,
        ?array             $event_data = null
    ): void
    {
        // Setup Handler stack.
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);
        $mockHandler = new MockHandler([
            $response_definition->getResponse()
        ]);
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        // Create client.
        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        // Call method.
        $fireEvent = $client->fireEvent(
            $event_type,
            $event_data
        );

        // Assert client returns correct data.
        $this->assertSame($response_definition->getBodyAsArray(), $fireEvent);

        // Assert request sent correctly.
        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame($event_data, json_decode($request->getBody()->getContents(), true));

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . "events/{$event_type}"
        );
    }

    public static function client_can_fire_event_provider(): Generator
    {
        yield 'home_assistant_start' => [
            'response_definition' => new FireEventHomeAssistantStart(),
            'event_type'          => 'home_assistant_start',
            'event_data'          => null,
        ];

        yield 'home_assistant_stop' => [
            'response_definition' => new FireEventHomeAssistantStop(),
            'event_type'          => 'home_assistant_stop',
            'event_data'          => null,
        ];

        yield 'script_started' => [
            'response_definition' => new FireEventScriptStarted(),
            'event_type'          => 'script_started',
            'event_data'          => [
                'name'      => 'Turn All Lights Off',
                'entity_id' => 'script.turn_all_lights_off'
            ],
        ];
    }

    #[Test]
    public function client_can_call_service(): void
    {
        $responseDefinition = new CallService();

        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            $responseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack,
        );

        $payload = [
            'entity_id' => 'light.bedroom_ceiling'
        ];

        $response = $client->callService(Domain::LIGHT, Service::TURN_ON, $payload);

        $this->assertSame($responseDefinition->getBodyAsArray(), $response);

        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame($payload, json_decode($request->getBody()->getContents(), true));

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'services/light/turn_on'
        );
    }

    #[Test]
    #[DataProvider('call_service_handles_error_provider')]
    public function call_service_handles_error(ResponseDefinition $response_definition, string $expected_exception_message): void
    {
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            $response_definition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $payload = [];

        try {
            $response = $client->callService(Domain::LIGHT, Service::TURN_ON, $payload);
            $this->fail();
        } catch (HaException $haException) {
            $this->assertSame($expected_exception_message, $haException->getMessage());
        }

        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame($payload, json_decode($request->getBody()->getContents(), true));

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'services/light/turn_on'
        );
    }

    public static function call_service_handles_error_provider(): Generator
    {
        yield 'bad request' => [
            'response_definition'        => $badRequest = new BadRequest(),
            'expected_exception_message' => $badRequest->bodyContent
        ];

        yield 'invalid json' => [
            'response_definition'        => new InvalidJson(),
            'expected_exception_message' => 'Invalid JSON Response.',
        ];

        yield 'server error' => [
            'response_definition'        => new ServerError(),
            'expected_exception_message' => 'Unknown Error.'
        ];
    }

    #[Test]
    #[DataProvider('client_can_render_template_provider')]
    public function client_can_render_template(
        ResponseDefinition $response_definition,
        string             $template,
        bool               $expect_error,
        ?string            $expected_error_message = null,
    ): void
    {
        // Setup Handler stack.
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $response_definition->getResponse(),
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        // Create client.
        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        // Call method.
        try {

            $renderedTemplate = $client->renderTemplate($template);
            if ($expect_error) {
                $this->fail('Should have failed.');
            }

            // Assert client returns correct data.
            $this->assertSame(['response' => $response_definition->bodyContent], $renderedTemplate);

        } catch (HaException $haException) {
            if (!$expect_error) {
                $this->fail('Should not have failed.');
            }
            $this->assertSame($expected_error_message, $haException->getMessage());
        }

        // Assert request sent correctly.
        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame(['template' => $template], json_decode($request->getBody()->getContents(), true));

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'template'
        );
    }

    public static function client_can_render_template_provider(): Generator
    {
        yield 'success' => [
            'response_definition'    => new RenderTemplateSuccess(),
            'template'               => "The bedroom ceiling light is {{ states('light.bedroom_ceiling') }}.",
            'expect_error'           => false,
            'expected_error_message' => null,
        ];

        yield 'failure - bad request' => [
            'response_definition'    => $badRequest = new RenderTemplateFailBadRequest(),
            'template'               => "The bedroom ceiling light is {{ statess('light.bedroom_ceiling') }}.",
            'expect_error'           => true,
            'expected_error_message' => $badRequest->bodyContent,
        ];
    }

    #[Test]
    public function client_can_check_config(): void
    {
        $checkConfigResponseDefinition = new CheckConfig();

        // Setup Handler stack.
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $checkConfigResponseDefinition->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        // Create client.
        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        // Call method.
        $checkConfig = $client->checkConfig();

        // Assert client returns correct data.
        $this->assertSame($checkConfigResponseDefinition->getBodyAsArray(), $checkConfig);

        // Assert request sent correctly.
        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('POST', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'config/core/check_config'
        );
    }

    #[Test]
    #[DataProvider('client_can_handle_intent_provider')]
    public function client_can_handle_intent(
        ResponseDefinition $response_definition,
        bool               $expect_error,
        ?string            $expected_error_message = null,
    ): void
    {
        // Setup Handler stack.
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $response_definition->getResponse(),
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        // Create client.
        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        // Call method.
        try {
            $payload = [
                'name' => 'SetTimer',
                'data' => [
                    'seconds' => '30',
                ]
            ];

            $handleIntent = $client->handleIntent($payload);
            if ($expect_error) {
                $this->fail('Should have failed.');
            }

            // Assert client returns correct data.
            $this->assertSame($response_definition->getBodyAsArray(), $handleIntent);

        } catch (HaException $haException) {
            if (!$expect_error) {
                $this->fail('Should not have failed.');
            }
            $this->assertSame($expected_error_message, $haException->getMessage());
        }

        // Assert request sent correctly.
        $this->assertCount(1, $historyContainer);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame($payload, json_decode($request->getBody()->getContents(), true));

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'intent/handle'
        );
    }

    public static function client_can_handle_intent_provider(): Generator
    {
        yield 'success' => [
            'response_definition'    => new HandleIntentSuccess(),
            'expect_error'           => false,
            'expected_error_message' => null,
        ];

        yield 'failure - bad request' => [
            'response_definition'    => new HandleIntentFailServerError(),
            'expect_error'           => true,
            'expected_error_message' => 'Unknown Error.',
        ];
    }

    #[Test]
    public function client_wraps_response_in_array_if_needed(): void
    {
        $mockHandler = new MockHandler([
            (new NullJson())->getResponse()
        ]);

        $handlerStack = HandlerStack::create($mockHandler);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $response = $client->status();

        $this->assertSame([null], $response);
    }

    /**
     * ---------------------------------------------------------------------------------
     * Helpers
     * ---------------------------------------------------------------------------------
     */
    private function performCommonGuzzleRequestAssertions(
        Request $request,
        string  $bearerToken,
        string  $url
    )
    {
        // Headers - Authorization
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertSame("Bearer {$bearerToken}", $request->getHeader('Authorization')[0]);
        // Headers - Content-Type
        $this->assertTrue($request->hasHeader('Content-Type'));
        $this->assertSame("application/json", $request->getHeader('Content-Type')[0]);

        // Uri
        $this->assertSame($url, $request->getUri()->__toString());
    }
}
