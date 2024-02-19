<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests;

use Generator;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use IndexZer0\HaRestApiClient\HaInstanceConfig;
use IndexZer0\HaRestApiClient\HaRestApiClient;
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
            new Response(200, body: json_encode(['message' => 'API running.'])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new HaRestApiClient(
            new HaInstanceConfig(),
            $bearerToken
        );

        $client->guzzleClient->getConfig('handler')->setHandler($handlerStack);

        $status = $client->status();

        $this->assertSame(['message' => 'API running.'], $status);

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
            new Response(200, body: json_encode(['message' => 'API running.'])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new HaRestApiClient(
            $haInstanceConfig,
            $this->defaultBearerToken,
        );

        $client->guzzleClient->getConfig('handler')->setHandler($handlerStack);

        $status = $client->status();

        $this->assertSame(['message' => 'API running.'], $status);

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
    public function client_can_get_status(): void
    {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, body: json_encode(['message' => 'API running.'])),
        ]);

        /*$mock = new MockHandler([
            new Response(404, body: '404: Not Founddsssd', reason: 'Not Found'),
        ]);*/

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new HaRestApiClient(
            new HaInstanceConfig(),
            'bearerToken'
        );

        $client->guzzleClient->getConfig('handler')->setHandler($handlerStack);

        $status = $client->status();

        $this->assertSame(['message' => 'API running.'], $status);

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
     * ---------------------------------------------------------------------------------
     * Helpers
     * ---------------------------------------------------------------------------------
     */
    private function performCommonGuzzleRequestAssertions(
        Request $request,
        string $bearerToken,
        string $url
    ) {
        // Auth
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertSame("Bearer $bearerToken", $request->getHeader('Authorization')[0]);

        // Uri
        $this->assertSame($url, $request->getUri()->__toString());
    }
}
