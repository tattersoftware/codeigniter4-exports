<?php namespace Tatter\Exports\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class ExportsException extends \RuntimeException implements ExceptionInterface
{
	public static function forNoHandler($extension)
	{
		return new static(lang('Exports.noHandler', [$extension]));
	}
}
