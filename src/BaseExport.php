<?php namespace Tatter\Exports;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Handlers\Handlers\BaseHandler;
use Tatter\Handlers\Interfaces\HandlerInterface;

class BaseExport extends BaseHandler implements HandlerInterface
{	
	public function __construct(RequestInterface $request = null, ResponseInterface $response = null)
	{
		$this->request  = $request ?? service('request');
		$this->response = $response ?? service('response');
		$this->config   = config('Exports');
	}
}
