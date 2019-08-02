<?php namespace Tatter\Exports\Config;

use CodeIgniter\Config\BaseConfig;

class Handlers extends BaseConfig
{
	// Directory to search across namespaces for supported handlers
	public $directory = 'Exports';
	
	// Model used to track handlers
	public $model = '\Tatter\Exports\Models\ExportModel';
}
