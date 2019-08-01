<?php namespace Tatter\Exports\Exports;

use CodeIgniter\HTTP\DownloadResponse;
use Config\Services;
use Tatter\Exports\BaseExport;
use Tatter\Exports\Interfaces\ExportInterface;

class DownloadExport extends BaseExport implements ExportInterface
{
	public $definition = [
		'category' => 'Core',
		'name'     => 'Download',
		'uid'      => 'info',
		'icon'     => 'fas fa-file-download',
		'summary'  => 'Download a file straight from the browser',
	];
	public $extensions = ['*'];
	
	// Create a download response for the browser
	public function process(string $path, string $filename = null, string $mime = null)
	{
		// If no filename specified then use the name of the file
		$filename = $filename ?: pathinfo($path, PATHINFO_BASENAME);
		
		// Create the download response
		$response = new DownloadResponse($filename, (bool)$mime);
		$response->setFilePath($path);
		
		// Send it back
		return $response;
	}
}
