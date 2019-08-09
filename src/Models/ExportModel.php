<?php namespace Tatter\Exports\Models;

use CodeIgniter\Model;

class ExportModel extends Model
{
	protected $table      = 'exports';
	protected $primaryKey = 'id';

	protected $returnType = 'Tatter\Exports\Entities\Export';
	protected $useSoftDeletes = true;

	protected $allowedFields = ['name', 'uid', 'class', 'icon', 'summary', 'extensions'];

	protected $useTimestamps = true;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;

	// Retrieves a list of handlers that support a given extension
	public function getForExtension(string $extension)
	{
		return $this->builder()
			->like('extensions', $extension, 'both')
			->get()->getResult($this->returnType);
	}
}
