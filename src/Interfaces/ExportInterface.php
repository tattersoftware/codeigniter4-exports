<?php namespace Tatter\Exports\Interfaces;

use CodeIgniter\HTTP\RequestInterface;

interface ExportInterface
{
	public function __construct(RequestInterface $request);
	public function process(string $path, string $filename = null, string $mime = null);
}
