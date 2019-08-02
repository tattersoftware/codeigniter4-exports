<?php namespace Tatter\Exports\Entities;

use CodeIgniter\Entity;

class Export extends Entity
{
	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
	
	// Load the class and pass to the handler
	public function process(...$args)
	{
		$class = $this->attributes['class'];
		$handler = new $class();
		return $handler->process(...$args);
	}
}
