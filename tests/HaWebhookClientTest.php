<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests;

use Generator;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Mock\Client;
use IndexZer0\HaRestApiClient\Exception\HaExceptionInterface;
use IndexZer0\HaRestApiClient\HaWebhookClient;
use IndexZer0\HaRestApiClient\HttpClient\Builder;
use IndexZer0\HaRestApiClient\Tests\ResponseDefinitions\Webhook\WebhookSuccess;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HaWebhookClientTest extends TestCase
{
    private string $defaultBaseUri = 'http://localhost:8123/api/';

    private Client $mockClient;

    public function setUp(): void
    {
        $this->mockClient = new Client();
    }

    #[Test]
    #[DataProvider('client_can_send_webhook_provider')]
    public function client_can_send_webhook(
        bool    $expect_error,
        ?string $expected_exception_message,
        bool    $expect_request_sent,
        bool    $expect_content_type_header,
        ?string $expected_content_type_header_value,
        ?string $expected_uri,
        ?string $expected_request_body,
        string  $method,
        string  $webhook_id,
        ?array  $query_params,
        ?string $payload_type,
        ?array  $data,
    ): void {
        // Arrange
        $responseDefinition = new WebhookSuccess();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBaseUri);

        // Act
        try {
            $response = $client->send(
                $method,
                $webhook_id,
                $query_params,
                $payload_type,
                $data
            );
            if ($expect_error) {
                $this->fail('Should have failed.');
            }
        } catch (HaExceptionInterface $haException) {
            if (!$expect_error) {
                $this->fail('Should not have failed.');
            }
            $this->assertSame($expected_exception_message, $haException->getMessage());
        }

        // Assert
        if ($expect_request_sent) {
            $this->assertSame(['response' => $responseDefinition->bodyContent], $response);
            $this->assertCount(1, $this->mockClient->getRequests());

            $request = $this->mockClient->getLastRequest();

            // Assert request method
            $this->assertSame($method, $request->getMethod());

            // Headers - Content-Type
            $this->assertSame($expect_content_type_header, $request->hasHeader('Content-Type'));
            if ($expect_content_type_header) {
                $this->assertSame($expected_content_type_header_value, $request->getHeader('Content-Type')[0]);
            }

            $this->assertSame($expected_request_body, $request->getBody()->getContents());

            // Uri
            $this->assertSame($expected_uri, $request->getUri()->__toString());

        } else {
            $this->assertCount(0, $this->mockClient->getRequests());
        }
    }

    public static function client_can_send_webhook_provider(): Generator
    {
        $queryParams = ['query_one' => 1, 'query_two' => 2];

        yield 'expect_error | unsupported http method' => [
            'expect_error'               => true,
            'expected_exception_message' => '$method must be one of: GET, HEAD, PUT, POST',

            'expect_request_sent'                => false,
            'expect_content_type_header'         => false,
            'expected_content_type_header_value' => null,
            'expected_uri'                       => null,
            'expected_request_body'              => null,

            'method'       => 'unsupported',
            'webhook_id'   => '1',
            'query_params' => null,
            'payload_type' => null,
            'data'         => null,
        ];

        yield 'expect_error | unsupported payload type' => [
            'expect_error'               => true,
            'expected_exception_message' => '$payloadType must be one of: json, form_params',

            'expect_request_sent'                => false,
            'expect_content_type_header'         => false,
            'expected_content_type_header_value' => null,
            'expected_uri'                       => null,
            'expected_request_body'              => null,

            'method'       => 'POST',
            'webhook_id'   => '1',
            'query_params' => null,
            'payload_type' => 'unsupported',
            'data'         => null,
        ];

        yield 'expect_error | missing data when payload type is set' => [
            'expect_error'               => true,
            'expected_exception_message' => '$data must be provided when providing $payloadType',

            'expect_request_sent'                => false,
            'expect_content_type_header'         => false,
            'expected_content_type_header_value' => null,
            'expected_uri'                       => null,
            'expected_request_body'              => null,

            'method'       => 'POST',
            'webhook_id'   => '1',
            'query_params' => null,
            'payload_type' => 'json',
            'data'         => null,
        ];

        yield 'expect_request_sent | GET | without query' => [
            'expect_error'               => false,
            'expected_exception_message' => null,

            'expect_request_sent'                => true,
            'expect_content_type_header'         => false,
            'expected_content_type_header_value' => null,
            'expected_uri'                       => 'http://localhost:8123/api/webhook/1',
            'expected_request_body'              => '',

            'method'       => 'GET',
            'webhook_id'   => '1',
            'query_params' => null,
            'payload_type' => null,
            'data'         => null,
        ];

        yield 'expect_request_sent | GET | with query' => [
            'expect_error'               => false,
            'expected_exception_message' => null,

            'expect_request_sent'                => true,
            'expect_content_type_header'         => false,
            'expected_content_type_header_value' => null,
            'expected_uri'                       => 'http://localhost:8123/api/webhook/1?query_one=1&query_two=2',
            'expected_request_body'              => '',

            'method'       => 'GET',
            'webhook_id'   => '1',
            'query_params' => $queryParams,
            'payload_type' => null,
            'data'         => null,
        ];

        yield 'expect_request_sent | POST | json | without query' => [
            'expect_error'               => false,
            'expected_exception_message' => null,

            'expect_request_sent'                => true,
            'expect_content_type_header'         => true,
            'expected_content_type_header_value' => 'application/json',
            'expected_uri'                       => 'http://localhost:8123/api/webhook/1',
            'expected_request_body'              => '{"json_one":1,"json_two":2}',

            'method'       => 'POST',
            'webhook_id'   => '1',
            'query_params' => null,
            'payload_type' => 'json',
            'data'         => ['json_one' => 1, 'json_two' => 2],
        ];

        yield 'expect_request_sent | POST | json | with query' => [
            'expect_error'               => false,
            'expected_exception_message' => null,

            'expect_request_sent'                => true,
            'expect_content_type_header'         => true,
            'expected_content_type_header_value' => 'application/json',
            'expected_uri'                       => 'http://localhost:8123/api/webhook/1?query_one=1&query_two=2',
            'expected_request_body'              => '{"json_one":1,"json_two":2}',

            'method'       => 'POST',
            'webhook_id'   => '1',
            'query_params' => $queryParams,
            'payload_type' => 'json',
            'data'         => ['json_one' => 1, 'json_two' => 2],
        ];

        yield 'expect_request_sent | POST | form_params | without query' => [
            'expect_error'               => false,
            'expected_exception_message' => null,

            'expect_request_sent'                => true,
            'expect_content_type_header'         => true,
            'expected_content_type_header_value' => 'application/x-www-form-urlencoded',
            'expected_uri'                       => 'http://localhost:8123/api/webhook/1',
            'expected_request_body'              => 'json_one=1&json_two=2',

            'method'       => 'POST',
            'webhook_id'   => '1',
            'query_params' => null,
            'payload_type' => 'form_params',
            'data'         => ['json_one' => 1, 'json_two' => 2],
        ];

        yield 'expect_request_sent | POST | form_params | with query' => [
            'expect_error'               => false,
            'expected_exception_message' => null,

            'expect_request_sent'                => true,
            'expect_content_type_header'         => true,
            'expected_content_type_header_value' => 'application/x-www-form-urlencoded',
            'expected_uri'                       => 'http://localhost:8123/api/webhook/1?query_one=1&query_two=2',
            'expected_request_body'              => 'json_one=1&json_two=2',

            'method'       => 'POST',
            'webhook_id'   => '1',
            'query_params' => $queryParams,
            'payload_type' => 'form_params',
            'data'         => ['json_one' => 1, 'json_two' => 2],
        ];
    }

    #[Test]
    #[DataProvider('consumer_can_alter_plugins_provider')]
    public function consumer_can_alter_plugins(
        ?string $plugin_to_remove,
        string  $expected_request_uri,
    ): void {
        // Arrange
        $responseDefinition = new WebhookSuccess();
        $this->mockClient->addResponse($responseDefinition->getResponse());

        $client = $this->createClient($this->defaultBaseUri);

        if ($plugin_to_remove !== null) {
            $client->httpClientBuilder->removePlugin($plugin_to_remove);
        }

        // Act
        $client->send(
            'GET',
            'webhook_id',
        );

        $this->assertCount(1, $this->mockClient->getRequests());

        $request = $this->mockClient->getLastRequest();

        $this->assertSame($expected_request_uri, $request->getUri()->__toString());
    }

    public static function consumer_can_alter_plugins_provider(): Generator
    {
        yield 'expect_success | don\'t remove plugin' => [
            'plugin_to_remove'     => null,
            'expected_request_uri' => 'http://localhost:8123/api/webhook/webhook_id',
        ];

        yield 'expect_error | remove BaseUriPlugin plugin' => [
            'plugin_to_remove'     => BaseUriPlugin::class,
            'expected_request_uri' => '/webhook/webhook_id',
        ];
    }

    /**
     * ---------------------------------------------------------------------------------
     * Helpers
     * ---------------------------------------------------------------------------------
     */

    private function createClient(string $baseUri): HaWebhookClient
    {
        return new HaWebhookClient(
            $baseUri,
            new Builder(
                $this->mockClient
            )
        );
    }
}
