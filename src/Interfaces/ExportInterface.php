<?php namespace Tatter\Exports\Interfaces;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RequestInterface;

interface ExportInterface
{
	public function __construct(RequestInterface $request = null, ResponseInterface $response = null);
	public function process(string $path, string $filename = null, string $mime = null);
}
