<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient;

use Http\Client\Common\Plugin\BaseUriPlugin;
use IndexZer0\HaRestApiClient\Exception\InvalidArgumentException;
use IndexZer0\HaRestApiClient\HttpClient\Builder;
use IndexZer0\HaRestApiClient\Traits\HandlesRequests;
use SensitiveParameter;

class HaWebhookClient
{
    use HandlesRequests;

    private array $supportedPayloadTypes = [
        'json',
        'form_params',
    ];

    private array $supportedHttpMethods = [
        'GET',
        'HEAD',
        'PUT',
        'POST'
    ];

    public function __construct(
        private string          $baseUri,
        public readonly Builder $httpClientBuilder = new Builder(),
    ) {
        $this->httpClientBuilder->addPlugin(new BaseUriPlugin(
            $this->httpClientBuilder->getUriFactory()->createUri($this->baseUri),
            [
                // Always replace the host, even if this one is provided on the sent request. Available for AddHostPlugin.
                'replace' => true,
            ]
        ));
    }

    /*
     * https://www.home-assistant.io/docs/automation/trigger/#webhook-trigger
     * Webhooks support HTTP POST, PUT, HEAD, and GET requests.
     *
     * Note that a given webhook can only be used in one automation at a time. That is, only one automation trigger can use a specific webhook ID.
     *
     * https://www.home-assistant.io/docs/automation/trigger/#webhook-data
     *
     * Payloads may either be encoded as form data or JSON.
     * Depending on that, its data will be available in an automation template as either trigger.data or trigger.json
     * URL query parameters are also available in the template as trigger.query.
     *
     * Note that to use JSON encoded payloads, the Content-Type header must be set to application/json
     *
     * https://www.home-assistant.io/docs/automation/trigger/#webhook-security
     *
     * ----------------------------------------------------------------------------
     *
     * See templating for webhooks in automations.
     * https://www.home-assistant.io/docs/automation/templating/#all
     * https://www.home-assistant.io/docs/automation/templating/#webhook
     */
    public function send(
        string  $method,
        #[SensitiveParameter]
        string  $webhookId,
        ?array  $queryParams = null,
        ?string $payloadType = null,
        ?array  $data = null,
    ): array {
        if (!in_array($method, $this->supportedHttpMethods, true)) {
            throw new InvalidArgumentException("\$method must be one of: " . join(', ', $this->supportedHttpMethods));
        }

        $request = $this->createRequestWithQuery($method, "/webhook/{$webhookId}", $queryParams ?? []);

        if ($payloadType !== null) {
            if (!in_array($payloadType, $this->supportedPayloadTypes, true)) {
                throw new InvalidArgumentException("\$payloadType must be one of: " . join(', ', $this->supportedPayloadTypes));
            }

            if ($data === null) {
                throw new InvalidArgumentException("\$data must be provided when providing \$payloadType");
            }

            $request = $request->withHeader('Content-Type', $payloadType === 'json' ? 'application/json' : 'application/x-www-form-urlencoded');

            $request = $request->withBody(
                $this->httpClientBuilder->getStreamFactory()->createStream(
                    $payloadType === 'json' ? json_encode($data) : http_build_query($data, '', '&')
                )
            );
        }

        return $this->handleRequest(
            $request
        );
    }
}
