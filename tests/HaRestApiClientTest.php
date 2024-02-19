<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests;

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

    /**
     * @test
     *
     * @dataProvider client_sends_bearer_token_provider
     */
    public function client_sends_bearer_token($bearerToken): void
    {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getDefaultStatusResponse())),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new HaRestApiClient(
            new HaInstanceConfig(),
            $bearerToken,
            $handlerStack
        );

        $status = $client->status();

        $this->assertSame(Fixtures::getDefaultStatusResponse(), $status);

        $this->assertCount(1, $container);

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $bearerToken,
            $this->defaultBaseUri,
        );
    }

    public static function client_sends_bearer_token_provider(): Generator
    {
        yield 'bearer-1' => [
            'bearerToken' => 'bearer-1',
        ];

        yield 'bearer-2' => [
            'bearerToken' => 'bearer-2',
        ];
    }

    /**
     * @test
     *
     * @dataProvider client_uses_correct_instance_config_provider
     */
    public function client_uses_correct_instance_config(
        HaInstanceConfig $haInstanceConfig,
        string $expectedUrl
    ): void {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getDefaultStatusResponse())),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new HaRestApiClient(
            $haInstanceConfig,
            $this->defaultBearerToken,
            $handlerStack
        );

        $status = $client->status();

        $this->assertSame(Fixtures::getDefaultStatusResponse(), $status);

        $this->assertCount(1, $container);

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $expectedUrl
        );
    }

    public static function client_uses_correct_instance_config_provider(): Generator
    {
        yield 'default' => [
            'haInstanceConfig' => new HaInstanceConfig(),
            'expected_url' => 'http://localhost:8123/api/',
        ];

        yield 'different' => [
            'haInstanceConfig' => new HaInstanceConfig(
                'foreignhost',
                8124,
                '/api2/'
            ),
            'expected_url' => 'http://foreignhost:8124/api2/',
        ];
    }

    /**
     * @test
     */
    public function client_handles_unauthorized_response(): void
    {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            GuzzleHelpers::getUnauthorizedResponse()
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new HaRestApiClient(
            new HaInstanceConfig(),
            $this->defaultBearerToken,
            $handlerStack
        );

        try {
            $client->status();
            $this->fail();
        } catch (HaException $haException) {

        }

        $this->assertCount(1, $container);

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri,
        );
    }

    /**
     * @test
     */
    public function client_can_get_status(): void
    {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getDefaultStatusResponse())),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new HaRestApiClient(
            new HaInstanceConfig(),
            'bearerToken',
            $handlerStack
        );

        $status = $client->status();

        $this->assertSame(Fixtures::getDefaultStatusResponse(), $status);

        $this->assertCount(1, $container);

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri
        );
    }

    /**
     * @test
     */
    public function client_can_get_config(): void
    {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getDefaultConfigResponse())),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new HaRestApiClient(
            new HaInstanceConfig(),
            $this->defaultBearerToken,
            $handlerStack
        );

        $config = $client->config();

        $this->assertSame(Fixtures::getDefaultConfigResponse(), $config);

        $this->assertCount(1, $container);

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->assertSame('GET', $request->getMethod());

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'config'
        );
    }

    /**
     * @test
     */
    public function client_can_call_service(): void
    {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, body: json_encode(Fixtures::getCallServiceResponse())),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new HaRestApiClient(
            new HaInstanceConfig(),
            $this->defaultBearerToken,
            $handlerStack,
        );

        $payload = [
            'entity_id' => 'light.bedroom_ceiling'
        ];

        $response = $client->callService(Domain::LIGHT, Service::TURN_ON, $payload);

        $this->assertSame(Fixtures::getCallServiceResponse(), $response);

        $this->assertCount(1, $container);

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame($payload, json_decode($request->getBody()->getContents(), true));

        $this->performCommonGuzzleRequestAssertions(
            $request,
            $this->defaultBearerToken,
            $this->defaultBaseUri . 'services/light/turn_on'
        );
    }

    /**
     * @test
     * @dataProvider call_service_handles_error_provider
     */
    public function call_service_handles_error(Response $response, string $expectedExceptionMessage): void
    {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            $response
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new HaRestApiClient(
            new HaInstanceConfig(),
            $this->defaultBearerToken,
            $handlerStack
        );

        $payload = [];

        try {
            $response = $client->callService(Domain::LIGHT, Service::TURN_ON, $payload);
            $this->fail();
        } catch (HaException $haException) {
            $this->assertSame($expectedExceptionMessage, $haException->getMessage());
        }

        $this->assertCount(1, $container);

        /** @var Request $request */
        $request = $container[0]['request'];

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
            'request' => $badRequest = GuzzleHelpers::getBadRequestResponse(),
            'expected_exception_message' => $badRequest->getBody()->getContents()
        ];

        yield 'invalid json' => [
            'request' => GuzzleHelpers::getInvalidJsonResponse(),
            'expected_exception_message' => 'Invalid JSON Response.',
        ];
    }

    /**
     * ---------------------------------------------------------------------------------
     * Helpers
     * ---------------------------------------------------------------------------------
     */
    private function performCommonGuzzleRequestAssertions(
        Request $request,
        string $bearerToken,
        string $url
    ) {
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
