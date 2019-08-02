<?php namespace Tatter\Exports\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;
use Tatter\Exports\Models\ExportModel;

class ExportsList extends BaseCommand
{
    protected $group       = 'Exports';
    protected $name        = 'exports:list';
    protected $description = 'List all registered export handlers';
    
	protected $usage     = 'exports:list';
	protected $arguments = [ ];

	public function run(array $params = [])
    {
		$exports = new ExportModel();
		
		// get all exports
		$rows = $exports
			->select('id, name, category, uid, class, summary')
			->orderBy('name', 'asc')
			->get()->getResultArray();

		if (empty($rows))
		{
			CLI::write( CLI::color('There are no registered export handlers.', 'yellow') );
		}
		else
		{
			$thead = ['ID', 'Name', 'Category', 'UID', 'Class', 'Summary'];
			CLI::table($rows, $thead);
		}
	}
}
