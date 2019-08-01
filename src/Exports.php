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

	// Retrieves a list of handlers that support a given extension
	public function getForExtension(string $extension)
	{
		$this->ensureHandlers();

		// Check each supported handler for support
		$classes = [];
		foreach ($this->handlers as $class => $definition):
			if (in_array(strtolower($extension, $definition['extensions']))):
				$classes[] = $class;
			elseif (in_array(strtolower('*', $definition['extensions']))):
				$classes[] = $class;
			endif;
		endforeach;
		
		// Check for failure
		if (empty($classes)):
			$this->errors[] = lang('Exports.noHandler', [$input]);
			return false;
		endif;
		
		return $classes;
	}
	
	// Check for all supported extensions and their handlers
	protected function ensureHandlers()
	{
		if (! is_null($this->handlers))
			return true;
		if ($cached = cache('exportHandlers'))
			return true;
		
		$locator = Services::locator(true);

		// Get all namespaces from the autoloader
		$namespaces = Services::autoloader()->getNamespace();
		
		// Scan each namespace for export handlers
		$flag = false;
		foreach ($namespaces as $namespace => $paths):

			// Get any files in /Exports/ for this namespace
			$files = $locator->listNamespaceFiles($namespace, '/Exports/');
			foreach ($files as $file):
			
				// Skip non-PHP files
				if (substr($file, -4) !== '.php'):
					continue;
				endif;
				
				// Get namespaced class name
				$name = basename($file, '.php');
				$class = $namespace . '\Exports\\' . $name;
				
				include_once $file;

				// Validate the class
				if (! class_exists($class, false))
					continue;
				$instance = new $class();
				
				// Validate the property
				if (! isset($instance->definition))
					continue;
				
				// Store the handler definition by its class
				$this->handlers[$class] = $instance->definition;
			endforeach;
		endforeach;
		
		// Cache the results
		cache()->save('exportHandlers', $this->handlers, 300);
		
		return true;
	}
}
