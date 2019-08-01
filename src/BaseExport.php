<?php namespace Tatter\Exports;

use CodeIgniter\HTTP\RequestInterface;

class BaseExport
{
	public $definition;
	public $extensions;
	
	public function __construct(RequestInterface $request)
	{
		$this->request = $request;
		
		// Preload the model & config
		$this->config = config('Exports');
	}
}
