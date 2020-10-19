<?php namespace Tatter\Exports\Exports;

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\BaseExport;

class DownloadHandler extends BaseExport
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, mixed>
	 */
	public $attributes = [
		'name'       => 'Download',
		'slug'       => 'download',
		'icon'       => 'fas fa-file-download',
		'summary'    => 'Download a file straight from the browser',
		'extensions' => '*',
		'ajax'       => false,
		'direct'     => true,
		'bulk'       => false,
	];

	/**
	 * Creates a download response for the browser.
	 *
	 * @return ResponseInterface|null
	 */
	protected function _process(): ?ResponseInterface
	{
		$file = $this->getFile();
		$path = $file->getRealPath() ?: (string) $file;

		// Create the download response
		return $this->response->download($path, null, true)->setFileName($this->fileName);
	}
}
