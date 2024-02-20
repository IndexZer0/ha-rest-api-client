<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Tests\Fixtures;

use GuzzleHttp\Psr7\Response;

class GuzzleHelpers
{
    /*
     * https://developers.home-assistant.io/docs/api/rest/
     *
     * Successful calls will return status code 200 or 201. Other status codes that can return are:
     *
     * 400 (Bad Request)
     * 401 (Unauthorized)
     * 404 (Not Found)
     * 405 (Method Not Allowed)
     */

    /**
     * ---------------------------------------------------------------------------------
     * Expected Cases
     * ---------------------------------------------------------------------------------
     */

    public static function getBadRequestResponse(): Response
    {
        return new Response(400, body: '400: Bad Request', reason: 'Bad Request');
    }

    public static function getUnauthorizedResponse(): Response
    {
        return new Response(401, body: '401: Unauthorized', reason: 'Unauthorized');
    }

    public static function getNotFoundResponse(): Response
    {
        return new Response(404, body: '{"message":"Entity not found."}', reason: 'Not Found');
    }

    // TODO getMethodNotAllowedResponse

    /**
     * ---------------------------------------------------------------------------------
     * Other
     * ---------------------------------------------------------------------------------
     */

    public static function getInvalidJsonResponse(): Response
    {
        return new Response(200, body: 'invalid json');
    }

    public static function getNullJsonResponse(): Response
    {
        return new Response(200, body: 'null');
    }

    public static function getServerErrorResponse(): Response
    {
        return new Response(500, body: 'Server error');
    }
}
