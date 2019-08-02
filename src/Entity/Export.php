<?php namespace Tatter\Exports\Entities;

use CodeIgniter\Entity;

class Export extends Entity
{
	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
