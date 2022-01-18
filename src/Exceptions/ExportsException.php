<?php

namespace Tatter\Exports\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use RuntimeException;

class ExportsException extends RuntimeException implements ExceptionInterface
{
    public static function forNoHandler($extension)
    {
        return new static(lang('Exports.noHandler', [$extension])); // @phpstan-ignore-line
    }
}
