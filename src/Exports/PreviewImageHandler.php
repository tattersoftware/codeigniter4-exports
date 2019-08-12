<?php namespace Tatter\Exports\Exports;

use CodeIgniter\Events\Events;
use CodeIgniter\Files\File;
use Config\Services;
use Tatter\Exports\BaseExport;
use Tatter\Exports\Interfaces\ExportInterface;

class PreviewImageHandler extends BaseExport implements ExportInterface
{
	// Attributes for Tatter\Handlers
	public $attributes = [
		'name'       => 'Preview',
		'uid'        => 'previewimage',
		'icon'       => 'fas fa-image',
		'summary'    => 'Open an image in the browser',
		'extensions' => 'jpg,jpeg,gif,png,pdf,bmp,ico',
		'ajax'       => 1,
	];
		
	// Check for AJAX to tag image, otherwise read out the file directly
	public function process(string $path, string $filename = null, string $mime = null)
	{
		// Trigger an Export event
		Events::trigger('export', ['type' => $this->attributes['uid'], 'file' => $path]);
		
		// If no MIME was specified then read it from the file
		if (empty($mime)):
			 $file = new File($path);
			 $mime = $file->getMimeType();
		endif;

		// Intercept AJAX requests
		if ($this->request->isAJAX())
			return $this->processAJAX($path, $filename, $mime);

		// Set the headers and read out the file
		return $this->response
			->setHeader('Content-Type', $mime)
			->setBody(file_get_contents($path));
	}
	
	// Create an appropriate tag
	protected function processAJAX(string $path, string $filename = null, string $mime)
	{
		// Build the base64 tag
		$html  = '<img src="data:' . $mime . ';base64, ';
		$html .= base64_encode(file_get_contents($path));
		$html .= '" alt="' . $filename . '">';
		
		return $this->response->setBody($html);
/*
		// Vary response by image type
		switch (pathinfo($filename, PATHINFO_EXTENSION)):
			case 'jpg':
			case 'jpeg':
			case 'gif':
*/
	}
}
