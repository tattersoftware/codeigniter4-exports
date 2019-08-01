<?php namespace Tatter\Exports;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class BaseExport
{
	public $definition;
	
	public function __construct(RequestInterface $request = null, ResponseInterface $response = null)
	{
		$this->request  = $request ?? service('request');
		$this->response = $response ?? service('response');
		$this->config   = config('Exports');
	}
}
