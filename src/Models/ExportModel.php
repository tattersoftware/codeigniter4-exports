<?php namespace Tatter\Exports\Models;

use CodeIgniter\Model;

class ExportModel extends Model
{
	protected $table      = 'exports';
	protected $primaryKey = 'id';

	protected $returnType = 'Tatter\Exports\Entities\Export';
	protected $useSoftDeletes = true;

	protected $allowedFields = [
		'name', 'uid', 'class', 'icon', 'summary',
		'extensions', 'ajax', 'bulk',
	];

	protected $useTimestamps = true;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;
	
	// Keep a cache of handlers returned by extension
	protected $cachedByExtensions;
	
	// Retrieves a list of handlers that support a given extension
	public function getForExtension(string $extension): array
	{
		return $this->builder()
			->like('extensions', $extension, 'both')
			->get()->getResult($this->returnType);
	}
	
	// Retrieves a list of all handlers by extension
	public function getByExtensions(): array
	{
		if (! empty($this->cachedByExtensions))
			return $this->cachedByExtensions;
		
		$result = [];
		foreach ($this->findAll() as $export):
			foreach (explode(',', $export->extensions) as $extension):
				$result[$extension][] = $export;
			endforeach;
		endforeach;
		
		$this->cachedByExtensions = $result;
		return $result;
	}
}
