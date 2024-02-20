<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests;

use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Generator;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use IndexZer0\HaRestApiClient\Domain;
use IndexZer0\HaRestApiClient\HaException;
use IndexZer0\HaRestApiClient\HaInstanceConfig;
use IndexZer0\HaRestApiClient\HaRestApiClient;
use IndexZer0\HaRestApiClient\Service;
use IndexZer0\HaRestApiClient\Tests\Fixtures\Fixtures;
use IndexZer0\HaRestApiClient\Tests\Fixtures\GuzzleHelpers;
use PHPUnit\Framework\TestCase;

class HaRestApiClientTest extends TestCase
{
    private string $defaultBearerToken = 'bearerToken';

    private string $defaultBaseUri = 'http://localhost:8123/api/';

    #[Test]
    #[DataProvider('client_sends_bearer_token_provider')]
    public function client_sends_bearer_token(string $bearer_token): void
    {
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getDefaultStatusResponse())),
        ]);

        $handlerStack = HandlerStack::create($mockHandlerHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $bearer_token,
            new HaInstanceConfig(),
            $handlerStack
        );

        $status = $client->status();

        $this->assertSame(Fixtures::getDefaultStatusResponse(), $status);

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
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getDefaultStatusResponse())),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            $ha_instance_config,
            $handlerStack
        );

        $status = $client->status();

        $this->assertSame(Fixtures::getDefaultStatusResponse(), $status);

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
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            GuzzleHelpers::getUnauthorizedResponse()
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
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getDefaultStatusResponse())),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            'bearerToken',
            new HaInstanceConfig(),
            $handlerStack
        );

        $status = $client->status();

        $this->assertSame(Fixtures::getDefaultStatusResponse(), $status);

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
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getDefaultConfigResponse())),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $config = $client->config();

        $this->assertSame(Fixtures::getDefaultConfigResponse(), $config);

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
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getEventsResponse())),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $events = $client->events();

        $this->assertSame(Fixtures::getEventsResponse(), $events);

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
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getServicesResponse())),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($historyMiddleware);

        $client = new HaRestApiClient(
            $this->defaultBearerToken,
            new HaInstanceConfig(),
            $handlerStack
        );

        $services = $client->services();

        $this->assertSame(Fixtures::getServicesResponse(), $services);

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
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getHistoryResponse())),
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
            $this->assertSame(Fixtures::getHistoryResponse(), $history);
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
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getLogbookResponse())),
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
        $this->assertSame(Fixtures::getLogbookResponse(), $logbook);
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
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getStatesResponse())),
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
        $this->assertSame(Fixtures::getStatesResponse(), $states);
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
        Response $response,
        bool     $expect_error,
        ?string  $expected_error_message = null,
    ): void
    {
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            $response
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
            $this->assertSame(Fixtures::getStateResponse(), $state);
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
            'response'               => new Response(200, body: json_encode(Fixtures::getStateResponse())),
            'expect_error'           => false,
            'expected_error_message' => null,
        ];

        yield 'error' => [
            'response'               => GuzzleHelpers::getNotFoundResponse(),
            'expect_error'           => true,
            // TODO - handle this error message better.
            'expected_error_message' => '{"message":"Entity not found."}',
        ];
    }

    #[Test]
    public function client_can_get_error_log(): void
    {
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandlerHandler = new MockHandler([
            new Response(200, ['Content-Type' => 'text/plain'], body: Fixtures::getErrorLogResponse()),
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
        $this->assertSame(['response' => Fixtures::getErrorLogResponse()], $errorLog);
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
    public function client_can_call_service(): void
    {
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getCallServiceResponse())),
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

        $this->assertSame(Fixtures::getCallServiceResponse(), $response);

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
    public function call_service_handles_error(Response $response, string $expected_exception_message): void
    {
        $historyContainer = [];
        $historyMiddleware = Middleware::history($historyContainer);

        $mockHandler = new MockHandler([
            $response
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
            'response'                   => $badRequest = GuzzleHelpers::getBadRequestResponse(),
            'expected_exception_message' => $badRequest->getBody()->getContents()
        ];

        yield 'invalid json' => [
            'response'                   => GuzzleHelpers::getInvalidJsonResponse(),
            'expected_exception_message' => 'Invalid JSON Response.',
        ];
    }

    #[Test]
    public function client_wraps_response_in_array_if_needed(): void
    {
        $mockHandler = new MockHandler([
            GuzzleHelpers::getNullJsonResponse()
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
