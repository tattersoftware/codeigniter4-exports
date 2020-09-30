<?php namespace Tatter\Exports\Exports;

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\BaseExport;

class DownloadHandler extends BaseExport
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, mixed>  Expects: name, slug, icon, summary, extensions, ajax, direct, bulk
	 */
	public $attributes = [
		'name'       => 'Download',
		'slug'       => 'download',
		'icon'       => 'fas fa-file-download',
		'summary'    => 'Download a file straight from the browser',
		'extensions' => '*',
		'ajax'       => false,
		'direct'     => true,
		'bulk'       => true,
	];

	/**
	 * Creates a download response for the browser.
	 *
	 * @return ResponseInterface|null
	 */
	protected function _process(): ?ResponseInterface
	{
		// Create the download response
		return $this->response->download($this->file->getRealPath(), null, true)->setFileName($this->fileName);
	}
}
