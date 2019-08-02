<?php namespace Tatter\Exports;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;

class Exports
{
	/**
	 * The configuration instance.
	 *
	 * @var \Tatter\Exports\Config\Exports
	 */
	protected $config;
	
	/**
	 * Array of supported extensions and their handlers
	 *
	 * @var array
	 */
	protected $handlers;
	
	/**
	 * Array error messages assigned on failure
	 *
	 * @var array
	 */
	protected $errors;
	
	
	// initiate library
	public function __construct(BaseConfig $config)
	{		
		// Save the configuration
		$this->config = $config;
		
		// Check for cached version of discovered handlers
		$this->handlers = cache('exportHandlers');
	}
	
	// Return any error messages
	public function getErrors()
	{
		return $this->errors;
	}

	// Calls the requested handler on the supplied file
	public function process(string $uid, string $path, string $filename = null, string $mime = null)
	{
		//$handler = $this->getbyUid($uid);
	}
}
