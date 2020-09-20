<?php namespace Tests\Support\Exports;

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\BaseExport;

class MockExport extends BaseExport
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, mixed>  Expects: name, icon, summary, extensions, ajax, direct, bulk      
	 */
	public $attributes = [
		'name'       => 'Mock',
		'icon'       => 'fas fa-flask',
		'summary'    => 'Mock export handler',
		'extensions' => '*',
		'ajax'       => true,
		'direct'     => true,
		'bulk'       => true,
	];

	/**
	 * Blindly does nothing.
	 *
	 * @return ResponseInterface|null
	 */
	protected function _process(): ?ResponseInterface
	{
		return $this->response;
	}
}
