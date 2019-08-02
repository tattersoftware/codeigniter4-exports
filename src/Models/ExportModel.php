<?php namespace Tatter\Exports\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
	protected $table      = 'exports';
	protected $primaryKey = 'id';

	protected $returnType = 'Tatter\Exports\Entities\Export';
	protected $useSoftDeletes = true;

	protected $allowedFields = [
		'category', 'name', 'uid', 'class', 'icon', 'summary', 'extensions'
	];

	protected $useTimestamps = true;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;

	// Retrieves a list of handlers that support a given extension
	public function getForExtension(string $extension)
	{
		
	}
}
