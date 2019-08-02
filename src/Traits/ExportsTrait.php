<?php namespace Tatter\Exports\Traits;

use Tatter\Exports\Models\ExportModel;

/*** CLASS ***/
trait TasksTrait
{
	public $config;
	
	public function __construct()
	{		
		$this->config = config('Exports');			
		$this->model  = new ExportModel();
	}
	
	// magic wrapper for getting values from the definition
    public function __get(string $name)
    {
		return $this->definition[$name];
    }
	
	// create the database record of this task based on its definition
	public function register()
	{		
		// check for an existing entry
		if ($export = $this->model->where('uid', $this->uid)->first())
		{
			return true;
		}
		
		return $this->model->insert($this->definition, true);
	}
	
	// soft delete this task from the database
	public function remove()
	{
		return $this->model->where('uid', $this->uid)->delete();
	}
}
