<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests;

use DateTime;
use DateTimeInterface;
use Generator;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Http\Mock\Client;
use IndexZer0\HaRestApiClient\Exception\HaExceptionInterface;
use IndexZer0\HaRestApiClient\HaRestApiClient;
use IndexZer0\HaRestApiClient\HttpClient\Builder;
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
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class HaRestApiClientTest extends TestCase
{
    private string $defaultBearerToken = 'bearerToken';

    private string $defaultBaseUri = 'http://localhost:8123/api/';

    private Client $mockClient;

    public function setUp(): void
    {
        $this->mockClient = new Client();
    }

    #[Test]
    #[DataProvider('client_sends_bearer_token_provider')]
    public function client_sends_bearer_token(string $bearer_token): void
    {
        // Arrange
        $responseDefinition = new Status();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($bearer_token, $this->defaultBaseUri);

        // Act
        $status = $client->status();

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $status);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
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
    #[DataProvider('client_uses_correct_base_uri_provider')]
    public function client_uses_correct_base_uri(
        string $base_uri,
        string $expected_url
    ): void {
        // Arrange
        $responseDefinition = new Status();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $base_uri);

        // Act
        $status = $client->status();

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $status);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
            $this->defaultBearerToken,
            $expected_url
        );
    }

    public static function client_uses_correct_base_uri_provider(): Generator
    {
        yield 'default' => [
            'base_uri'     => 'http://localhost:8123/api/',
            'expected_url' => 'http://localhost:8123/api/',
        ];

        yield 'different' => [
            'base_uri'     => 'http://foreignhost:8124/api2/',
            'expected_url' => 'http://foreignhost:8124/api2/',
        ];
    }

    #[Test]
    public function client_handles_unauthorized_response(): void
    {
        // Arrange
        $responseDefinition = new Auth();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        try {
            $client->status();
            $this->fail('Should have failed');
        } catch (HaExceptionInterface $haException) {
            $this->assertSame($responseDefinition->bodyContent, $haException->getMessage());
        }

        // Assert
        $this->performCommonRequestAssertionsMockClient(
            'GET',
            $this->defaultBearerToken,
            $this->defaultBaseUri,
        );
    }

    #[Test]
    public function client_can_get_status(): void
    {
        // Arrange
        $responseDefinition = new Status();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $status = $client->status();

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $status);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
            $this->defaultBearerToken,
            $this->defaultBaseUri
        );
    }

    #[Test]
    public function client_can_get_status_using_guzzle(): void
    {
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        // Arrange
        $responseDefinition = new Status();
        $mockHandler->append($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri, new \GuzzleHttp\Client([
            'handler' => $handlerStack,
        ]));

        // Act
        $status = $client->status();

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $status);
        $this->performCommonRequestAssertionsGuzzle(
            'GET',
            $this->defaultBearerToken,
            $this->defaultBaseUri,
            $historyContainer,
        );
    }

    public function setupHandlerStack(): void
    {

    }

    #[Test]
    public function client_can_get_config(): void
    {
        // Arrange
        $responseDefinition = new Config();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $config = $client->config();

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $config);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'config'
        );
    }

    #[Test]
    public function client_can_get_events(): void
    {
        // Arrange
        $responseDefinition = new Events();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $events = $client->events();

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $events);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'events'
        );
    }

    #[Test]
    public function client_can_get_services(): void
    {
        // Arrange
        $responseDefinition = new Services();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $services = $client->services();

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $services);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
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
        bool               $expect_request_sent,
        ?string            $expected_error_message = null,
        ?string            $expected_url = null,
        ?DateTimeInterface $start_time = null,
        ?DateTimeInterface $end_time = null,
    ): void {
        // Arrange
        $responseDefinition = new History();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
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
        } catch (HaExceptionInterface $haException) {
            if (!$expect_error) {
                $this->fail('Should not have failed.');
            }
            $this->assertSame($expected_error_message, $haException->getMessage());
        }

        // Assert
        if ($expect_request_sent) {
            $this->assertSame($responseDefinition->getBodyAsArray(), $history);
            $this->performCommonRequestAssertionsMockClient(
                'GET',
                $this->defaultBearerToken,
                $this->defaultBaseUri . $expected_url
            );
        } else {
            $this->assertCount(0, $this->mockClient->getRequests());
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
    ): void {
        // Arrange
        $responseDefinition = new Logbook();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $logbook = $client->logbook(
            $entity_id,
            $start_time,
            $end_time,
        );

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $logbook);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
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
        // Arrange
        $responseDefinition = new States();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $states = $client->states();

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $states);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
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
    ): void {
        // Arrange
        //dd($response_definition->getResponse());
        $this->mockClient->addResponse($response_definition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        try {
            $state = $client->state('light.bedroom_ceiling');
            if ($expect_error) {
                $this->fail('Should have failed.');
            }
            $this->assertSame($response_definition->getBodyAsArray(), $state);
        } catch (HaExceptionInterface $haException) {
            if (!$expect_error) {
                $this->fail('Should not have failed.');
            }
            $this->assertSame($expected_error_message, $haException->getMessage());
        }

        // Assert
        $this->performCommonRequestAssertionsMockClient(
            'GET',
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
        // Arrange
        $responseDefinition = new ErrorLog();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $errorLog = $client->errorLog();

        // Assert
        $this->assertSame(['response' => $responseDefinition->bodyContent], $errorLog);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'error_log'
        );
    }

    #[Test]
    public function client_can_get_calendars(): void
    {
        // Arrange
        $responseDefinition = new Calendars();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $calendars = $client->calendars();

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $calendars);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'calendars'
        );
    }

    #[Test]
    public function client_can_get_calendar_events(): void
    {
        // Arrange
        $responseDefinition = new CalendarEvents();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $calendarEvents = $client->calendarEvents(
            'calendar.birthdays',
            new DateTime('2024-02-10 00:00:00'),
            new DateTime('2024-02-20 23:59:59'),
        );

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $calendarEvents);
        $this->performCommonRequestAssertionsMockClient(
            'GET',
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'calendars/calendar.birthdays?start=2024-02-10T12%3A02%3A00%2B00%3A00&end=2024-02-20T11%3A02%3A59%2B00%3A00'
        );
    }

    #[Test]
    #[DataProvider('client_can_update_state_provider')]
    public function client_can_update_state(ResponseDefinition $response_definition): void
    {
        // Arrange
        $this->mockClient->addResponse($response_definition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
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

        // Assert
        $this->assertSame($response_definition->getBodyAsArray(), $updateState);
        $request = $this->performCommonRequestAssertionsMockClient(
            'POST',
            $this->defaultBearerToken,
            $this->defaultBaseUri . "states/{$entityId}"
        );
        $this->assertSame($payload, json_decode($request->getBody()->getContents(), true));
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
    ): void {
        // Arrange
        $this->mockClient->addResponse($response_definition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $fireEvent = $client->fireEvent(
            $event_type,
            $event_data
        );

        // Assert
        $this->assertSame($response_definition->getBodyAsArray(), $fireEvent);
        $request = $this->performCommonRequestAssertionsMockClient(
            'POST',
            $this->defaultBearerToken,
            $this->defaultBaseUri . "events/{$event_type}"
        );
        $this->assertSame($event_data, json_decode($request->getBody()->getContents(), true));
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
        // Arrange
        $responseDefinition = new CallService();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $payload = [
            'entity_id' => 'light.bedroom_ceiling'
        ];
        $response = $client->callService('light', 'turn_on', $payload);

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $response);
        $request = $this->performCommonRequestAssertionsMockClient(
            'POST',
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'services/light/turn_on'
        );
        $this->assertSame($payload, json_decode($request->getBody()->getContents(), true));
    }

    #[Test]
    #[DataProvider('call_service_handles_error_provider')]
    public function call_service_handles_error(ResponseDefinition $response_definition, string $expected_exception_message): void
    {
        // Arrange
        $this->mockClient->addResponse($response_definition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $payload = [];
        try {
            $client->callService('light', 'turn_on', $payload);
            $this->fail('Should have failed');
        } catch (HaExceptionInterface $haException) {
            $this->assertSame($expected_exception_message, $haException->getMessage());
        }

        // Assert
        $request = $this->performCommonRequestAssertionsMockClient(
            'POST',
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'services/light/turn_on'
        );
        $this->assertSame($payload, json_decode($request->getBody()->getContents(), true));
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
            'response_definition'        => $serverError = new ServerError(),
            'expected_exception_message' => $serverError->bodyContent
        ];
    }

    #[Test]
    #[DataProvider('client_can_render_template_provider')]
    public function client_can_render_template(
        ResponseDefinition $response_definition,
        string             $template,
        bool               $expect_error,
        ?string            $expected_error_message = null,
    ): void {
        // Arrange
        $this->mockClient->addResponse($response_definition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        try {
            $renderedTemplate = $client->renderTemplate($template);
            if ($expect_error) {
                $this->fail('Should have failed.');
            }

            $this->assertSame(['response' => $response_definition->bodyContent], $renderedTemplate);
        } catch (HaExceptionInterface $haException) {
            if (!$expect_error) {
                $this->fail('Should not have failed.');
            }
            $this->assertSame($expected_error_message, $haException->getMessage());
        }

        // Assert
        $request = $this->performCommonRequestAssertionsMockClient(
            'POST',
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'template'
        );
        $this->assertSame(['template' => $template], json_decode($request->getBody()->getContents(), true));
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
        // Arrange
        $responseDefinition = new CheckConfig();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $checkConfig = $client->checkConfig();

        // Assert
        $this->assertSame($responseDefinition->getBodyAsArray(), $checkConfig);
        $this->performCommonRequestAssertionsMockClient(
            'POST',
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
    ): void {
        // Arrange
        $this->mockClient->addResponse($response_definition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
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

            $this->assertSame($response_definition->getBodyAsArray(), $handleIntent);
        } catch (HaExceptionInterface $haException) {
            if (!$expect_error) {
                $this->fail('Should not have failed.');
            }
            $this->assertSame($expected_error_message, $haException->getMessage());
        }

        // Assert
        $request = $this->performCommonRequestAssertionsMockClient(
            'POST',
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'intent/handle'
        );
        $this->assertSame($payload, json_decode($request->getBody()->getContents(), true));
    }

    public static function client_can_handle_intent_provider(): Generator
    {
        yield 'success' => [
            'response_definition'    => new HandleIntentSuccess(),
            'expect_error'           => false,
            'expected_error_message' => null,
        ];

        yield 'failure - bad request' => [
            'response_definition'    => $serverError = new HandleIntentFailServerError(),
            'expect_error'           => true,
            'expected_error_message' => $serverError->bodyContent,
        ];
    }

    #[Test]
    public function client_wraps_response_in_array_if_needed(): void
    {
        // Arrange
        $responseDefinition = new NullJson();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBearerToken, $this->defaultBaseUri);

        // Act
        $response = $client->status();

        // Assert
        $this->assertSame([null], $response);
    }

    /**
     * ---------------------------------------------------------------------------------
     * Helpers
     * ---------------------------------------------------------------------------------
     */

    private function createClient(string $bearerToken, string $baseUri, ClientInterface $httpClient = null): HaRestApiClient
    {
        return new HaRestApiClient(
            $bearerToken,
            $baseUri,
            new Builder(
                $httpClient ?? $this->mockClient
            ),
        );
    }

    private function performCommonRequestAssertionsMockClient(
        string $method,
        string $bearerToken,
        string $url
    ): RequestInterface {
        // Assert a request was sent.
        $this->assertCount(1, $this->mockClient->getRequests());

        return $this->performCommonRequestAssertions(
            $this->mockClient->getLastRequest(),
            $method,
            $bearerToken,
            $url,
        );
    }

    private function performCommonRequestAssertionsGuzzle(
        string $method,
        string $bearerToken,
        string $url,
        array $historyContainer,
    ): RequestInterface {
        // Assert a request was sent.
        $this->assertCount(1, $historyContainer);

        return $this->performCommonRequestAssertions(
            $historyContainer[0]['request'],
            $method,
            $bearerToken,
            $url,
        );
    }

    private function performCommonRequestAssertions(
        RequestInterface $request,
        string $method,
        string $bearerToken,
        string $url
    ): RequestInterface {
        // Assert request method
        $this->assertSame($method, $request->getMethod());

        // Headers - Authorization
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertSame("Bearer {$bearerToken}", $request->getHeader('Authorization')[0]);
        // Headers - Content-Type
        $this->assertTrue($request->hasHeader('Content-Type'));
        $this->assertSame("application/json", $request->getHeader('Content-Type')[0]);

        // Uri
        $this->assertSame($url, $request->getUri()->__toString());

        return $request;
    }
}
