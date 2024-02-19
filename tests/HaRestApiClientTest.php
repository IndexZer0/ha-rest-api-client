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

        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertSame("Bearer $bearerToken", $request->getHeader('Authorization')[0]);
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
}
