<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Traits;

use Http\Client\Common\Exception\ClientErrorException;
use IndexZer0\HaRestApiClient\HaException;
use JsonException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

trait HandlesRequests
{
    /*
     * Send request and handle responses.
     */
    private function handleRequest(RequestInterface $request): array
    {
        try {
            $response = $this->httpClientBuilder->getHttpClient()->sendRequest($request);
        } catch (ClientErrorException $ce) {
            throw new HaException($ce->getResponse()->getBody()->getContents(), previous: $ce);
        } catch (Throwable $t) {
            throw new HaException('Unknown Error.', previous: $t);
        }

        $responseBodyContent = $response->getBody()->getContents();

        $responseContentType = $this->getContentTypeFromResponse($response) ?? 'application/json';

        if ($responseContentType === 'application/json') {
            try {
                $json = json_decode($responseBodyContent, true, flags: JSON_THROW_ON_ERROR);

                // This is a failsafe for if the home assistant json response is not an array when decoded
                // For example if $responseBodyContent = 'null';
                // Not seen this scenario in the wild but handling this json decode case anyway.
                if (!is_array($json)) {
                    return [$json];
                }

                return $json;
            } catch (JsonException $je) {
                // This should never happen.
                // If it does, it means home assistant is returning invalid json with application/json Content-Type header.
                throw new HaException('Invalid JSON Response.', previous: $je);
            }
        }

        // Some responses come back with Content-Type header of text/plain.
        // Such as errorLog and renderTemplate.
        // So lets just wrap in an array to satisfy return type and keep api consistent.
        return [
            'response' => $responseBodyContent
        ];
    }

    private function getContentTypeFromResponse(ResponseInterface $response): ?string
    {
        return $response->hasHeader('Content-Type') ? $response->getHeader('Content-Type')[0] : null;
    }

    private function createRequestWithQuery(string $method, $uri, array $query): RequestInterface
    {
        $request = $this->httpClientBuilder->getRequestFactory()->createRequest($method, $uri);
        return $request->withUri(
            $request->getUri()->withQuery(http_build_query($query))
        );
    }
}
