<?php namespace Tatter\Exports\Exports;

use CodeIgniter\Events\Events;
use Config\Services;
use Tatter\Exports\BaseExport;
use Tatter\Exports\Interfaces\ExportInterface;

class DownloadHandler extends BaseExport implements ExportInterface
{
	// Attributes for Tatter\Handlers
	public $attributes = [
		'name'       => 'Download',
		'uid'        => 'download',
		'icon'       => 'fas fa-file-download',
		'summary'    => 'Download a file straight from the browser',
		'extensions' => '*',
	];
		
	// Create a download response for the browser
	public function process(string $path, string $filename = null, string $mime = null)
	{
		// Trigger an Export event
		Events::trigger('export', ['type' => $this->attributes['uid'], 'file' => $path]);

		// If no filename specified then use the name of the file
		$filename = $filename ?: pathinfo($path, PATHINFO_BASENAME);
		
		// Create the download response
		return $this->response->download($path, null, (bool)$mime, $filename);
	}
}
