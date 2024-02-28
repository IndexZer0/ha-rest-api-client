<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Exception;

use Throwable;

class UnknownErrorException extends HaException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Unknown Error.', $code, $previous);
    }
}
