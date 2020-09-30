<?php namespace Tatter\Exports\Exports;

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\BaseExport;

class ImageHandler extends BaseExport
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, mixed>  Expects: name, slug, icon, summary, extensions, ajax, direct, bulk
	 */
	public $attributes = [
		'name'       => 'Preview',
		'slug'       => 'preview',
		'icon'       => 'fas fa-image',
		'summary'    => 'Open an image in the browser',
		'extensions' => 'jpg,jpeg,gif,png,pdf,bmp,ico',
		'ajax'       => true,
		'direct'     => true,
		'bulk'       => false,
	];

	/**
	 * Checks for AJAX to tag image, otherwise reads out the file directly.
	 *
	 * @return ResponseInterface|null
	 */
	protected function _process(): ?ResponseInterface
	{
		return $this->request->isAJAX()
			? $this->processAJAX()
			: $this->processDirect();
	}

	/**
	 * Reads the file out directly to browser.
	 *
	 * @return ResponseInterface
	 */
	protected function processDirect(): ResponseInterface
	{
		// Set the headers and read out the file
		return $this->response
			->setHeader('Content-Type', $this->fileMime)
			->setBody(file_get_contents($this->file->getRealPath()));
	}

	/**
	 * Creates an appropriate HTML tag.
	 *
	 * @return ResponseInterface
	 */
	protected function processAJAX(): ResponseInterface
	{
		return $this->response->setBody(view('\Tatter\Exports\Views\image', [
			'fileName' => $this->fileName,
			'fileMime' => $this->fileMime,
			'data'     => base64_encode(file_get_contents($this->file->getRealPath())),
		]));
	}
}
