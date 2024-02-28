<?php

declare(strict_types=1);

namespace IndexZer0\HaRestApiClient\Exception;

use InvalidArgumentException as BaseInvalidArgumentException;

class InvalidArgumentException extends BaseInvalidArgumentException implements HaExceptionInterface
{
}
