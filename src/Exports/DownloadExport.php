<?php namespace Tatter\Exports\Exports;

use CodeIgniter\Events\Events;
use Config\Services;
use Tatter\Exports\BaseExport;
use Tatter\Exports\Interfaces\ExportInterface;

class DownloadExport extends BaseExport implements ExportInterface
{
	use \Tatter\Exports\Traits\ExportsTrait;
	
	public $definition = [
		'category'   => 'Core',
		'name'       => 'Download',
		'uid'        => 'download',
		'icon'       => 'fas fa-file-download',
		'summary'    => 'Download a file straight from the browser',
		'extensions' => ['*'],
	];
		
	// Create a download response for the browser
	public function process(string $path, string $filename = null, string $mime = null)
	{
		// Trigger a Download event
		Events::trigger('download', $path);

		// If no filename specified then use the name of the file
		$filename = $filename ?: pathinfo($path, PATHINFO_BASENAME);
		
		// Create the download response
		return $this->response->download($path, null, (bool)$mime, $filename);
	}
}
