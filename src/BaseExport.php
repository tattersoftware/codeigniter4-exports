<?php namespace Tatter\Exports;

use CodeIgniter\Events\Events;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
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
	 * @var array<string, mixed>      
	 */
	public $attributes;

	/**
	 * Default set of attributes
	 *
	 * @var array<string, mixed>
	 */
	private $defaults = [
		'name'       => '',
		'slug'       => '',
		'icon'       => 'fas fa-external-link-alt',
		'summary'    => '',
		'extensions' => '*',
		'ajax'       => false,
		'direct'     => true,
		'bulk'       => false
	];

	/**
	 * Array of Files to export.
	 *
	 * @var File[]
	 */
	private $files = [];

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

		// Merge default attributes to be sure all are present
		$this->attributes = array_merge($this->defaults, $this->attributes);
	}

	/**
	 * Gets the next File, shortening the array.
	 *
	 * @return File|null
	 */
	public function getFile(): ?File
	{
		return array_shift($this->files);
	}

	/**
	 * Gets all Files without modifying the array.
	 *
	 * @return File[]
	 */
	public function getFiles(): array
	{
		return $this->files;
	}

	//--------------------------------------------------------------------

	/**
	 * Set the target File, or append a file for bulk handlers.
	 *
	 * @param File|string|null $file  The File or path to export, null to reset
	 *
	 * @throws FileNotFoundException If supplied a path to a non-existant file
	 */
	public function setFile($file): self
	{
		if (is_string($file))
		{
			$file = new File($file, true);
		}

		if (is_null($file))
		{
			$this->files = [];
		}
		elseif ($this->attributes['bulk'])
		{
			$this->files[] = $file;
		}
		else
		{
			$this->files = [$file];
		}

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

	//--------------------------------------------------------------------

	/**
	 * Wrapper for child process method.
	 *
	 * @return ResponseInterface|null
	 */
	public function process(): ?ResponseInterface
	{
		if (empty($this->files))
		{
			throw new ExportsException(lang('Exports.noFile'));
		}
		$file = reset($this->files);

		// If no file name was specified then set it to the base name
		$this->fileName = $this->fileName ?? $file->getBasename();

		// If no MIME was specified then read it from the file
		$this->fileMime = $this->fileMime ?? $file->getMimeType();

		// Trigger an Export event
		Events::trigger('export', [
			'handler'  => $this->toArray(),
			'file'     => $file->getRealPath() ?: (string) $file,
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
