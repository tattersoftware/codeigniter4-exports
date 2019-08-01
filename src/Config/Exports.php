<?php namespace Tatter\Exports\Config;

use CodeIgniter\Config\BaseConfig;

class Exports extends BaseConfig
{
	// Whether to continue instead of throwing exceptions
	public $silent = true;
	
	// Session variable to check for a logged-in user ID
	public $userSource = 'logged_in';
}
