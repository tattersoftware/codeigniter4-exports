<?php namespace Tatter\Exports;

use CodeIgniter\Events\Events;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\Exceptions\ExportsException;
use Tatter\Handlers\BaseHandler;

abstract class BaseExport extends BaseHandler
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, mixed>  Expects: name, icon, summary, extensions, ajax, direct, bulk      
	 */
	public $attributes;

	/**
	 * Target File to export.
	 *
	 * @var File|null
	 */
	protected $file;

	/**
	 * Alternate name to use for the file.
	 *
	 * @var string|null
	 */
	protected $fileName;

	/**
	 * Overriding MIME type to use.
	 *
	 * @var string|null
	 */
	protected $fileMime;

	/**
	 * Instance of the main Request object.
	 *
	 * @var RequestInterface
	 */
	protected $request;

	/**
	 * Instance of the main response object.
	 *
	 * @var ResponseInterface
	 */
	protected $response;

	/**
	 * Sets or loads the Request and Response objects.
	 *
	 * @param File|string|null   $file
	 * @param RequestInterface   $request
	 * @param ResponseInterface  $response
	 */
	public function __construct($file = null, RequestInterface $request = null, ResponseInterface $response = null)
	{
		$this->setFile($file);

		$this->request  = $request ?? service('request');
		$this->response = $response ?? service('response');
	}

	/**
	 * Set the target File.
	 *
	 * @param File|string $file  The file to export
	 */
	public function setFile($file): self
	{
		if (is_string($file))
		{
			$file = new File($file);
		}
		$this->file = $file;

		return $this;
	}

	/**
	 * Set the alternate file name.
	 *
	 * @param string|null $fileName
	 */
	public function setFileName(string $fileName = null): self
	{
		$this->fileName = $fileName;

		return $this;
	}

	/**
	 * Set the MIME type.
	 *
	 * @param string|null $fileMime
	 */
	public function setFileMime(string $fileMime = null): self
	{
		$this->fileMime = $fileMime;

		return $this;
	}

	/**
	 * Wrapper for child process method.
	 *
	 * @return ResponseInterface|null
	 */
	public function process(): ?ResponseInterface
	{
		if (is_null($this->file))
		{
			throw new ExportsException(lang('Exports.noFile'));
		}

		// If no file name was specified then set it to the base name
		$this->fileName = $this->fileName ?? $this->file->getBasename();

		// If no MIME was specified then read it from the file
		$this->fileMime = $this->fileMime ?? $this->file->getMimeType();

		// Trigger an Export event
		Events::trigger('export', [
			'handler'  => $this->toArray(),
			'file'     => $this->file->getRealPath(),
			'fileName' => $this->fileName,
			'fileMime' => $this->fileMime,
		]);

		return $this->_process();
	}

	/**
	 * Runs this Export process.
	 *
	 * @return ResponseInterface|null
	 */
	abstract protected function _process(): ?ResponseInterface;
}
