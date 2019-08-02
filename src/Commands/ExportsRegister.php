<?php namespace Tatter\Exports\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;
use Tatter\Exports\Models\ExportModel;

class ExportsRegister extends BaseCommand
{
    protected $group       = 'Exports';
    protected $name        = 'exports:register';
    protected $description = 'Search for new export handlers and add them to the database';
    
	protected $usage     = 'exports:register';
	protected $arguments = [ ];

	public function run(array $params = [])
    {
		$exports = new ExportModel();
		$locator = Services::locator(true);

		// Get all namespaces from the autoloader
		$namespaces = Services::autoloader()->getNamespace();
		
		// Scan each namespace for tasks
		$flag = false;
		foreach ($namespaces as $namespace => $paths):

			// get any files in /Exports/ for this namespace
			$files = $locator->listNamespaceFiles($namespace, '/Tasks/');
			foreach ($files as $file):
			
				// skip non-PHP files
				if (substr($file, -4) !== '.php'):
					continue;
				endif;
				
				// get namespaced class name
				$name = basename($file, '.php');
				$class = $namespace . '\Exports\\' . $name;
				
				include_once $file;

				// validate the class
				if (! class_exists($class, false)):
					throw new \RuntimeException("Could not locate {$class} in {$file}");
				endif;
				$instance = new $class();
				
				// validate the method
				if (! is_callable([$instance, 'register'])):
					throw new \RuntimeException("Missing 'register' method for {$class} in {$file}");
				endif;
				
				// register it
				$result = $instance->register();
				
				// if this was a new registration, add the namespaced class
				if (is_int($result)):
					$flag = true;
					
					$export = $exports->find($result);
					$export->class = $class;
					$exports->save($export);
				
					CLI::write("Registered {$export->name} from {$class}", 'green');
				endif;
				
			endforeach;
		endforeach;
		
		if ($flag == false):
			CLI::write('No new export handlers found in any namespace.', 'yellow');
			return;
		endif;
		
		$this->call('exports:list');
	}
}
