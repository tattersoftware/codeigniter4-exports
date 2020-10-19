<?php namespace Tatter\Exports\Exports;

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\BaseExport;
use Tatter\Exports\Exceptions\ExportsException;
use Phar;
use PharData;
use ZipArchive;

class ArchiveHandler extends BaseExport
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, mixed>
	 */
	public $attributes = [
		'name'       => 'Archive',
		'slug'       => 'archive',
		'icon'       => 'fas fa-download',
		'summary'    => 'Archive files and download from the browser',
		'extensions' => '*',
		'ajax'       => false,
		'direct'     => true,
		'bulk'       => true,
	];

	/**
	 * Archive format to use. "zip", "gzip", or blank to detect
	 *
	 * @var string
	 */
	protected $format = '';

	/**
	 * Sets the archive format
	 *
	 * @return $this
	 */
	public function setFormat(string $format): self
	{
		$this->format = $format;
		return $this;
	}

	/**
	 * Adds files to an archive then passes to DownloadHandler.
	 *
	 * @return ResponseInterface|null
	 *
	 * @throws ExportsException If one of the create methods fails
	 */
	protected function _process(): ?ResponseInterface
	{
		if ($this->format === 'gzip')
		{
			$path = $this->createGZip();
		}
		elseif ($this->format === 'zip')
		{
			$path = $this->createZip();
		}
		else
		{
			try
			{
				$path = $this->createZip();
			}
			catch (\Throwable $e)
			{
				$path = $this->createGZip();
			}
		}

		// Create the download response
		return $this->response->download($path, null, true)->setFileName($this->fileName);
	}

	/**
	 * Creates a zip file.
	 *
	 * @return string Path to the archive
	 *
	 * @throws ExportsException
	 */
	protected function createZip(): string
	{
		if (! class_exists('ZipArchive'))
		{
			throw new ExportsException('ZipArchive is not installed');
		}
		$archive = new ZipArchive;

		// Force an extension so DownloadResponse can set the MIME
		$path = tempnam(sys_get_temp_dir(), 'exports-') . '.zip';

		$result = $archive->open($path, ZipArchive::CREATE);
		if ($result !== true)
		{
			throw new ExportsException('ZipArchive failed during initialization', $result);			
		}

		while ($file = $this->getFile())
		{
			$filePath = $file->getRealPath() ?: (string) $file;
			if (! $archive->addFile($filePath, $file->getBasename()))
			{
				throw new ExportsException('ZipArchive failed to add a file: ' . $filePath);					
			}
		}

		if (! $archive->close())
		{
			throw new ExportsException('ZipArchive failed to create the archive');					
		}

		$this->setFileName('Archive-' . time() . '.zip');

		return $path;
	}

	/**
	 * Creates a tar.gz file.
	 *
	 * @return string Path to the archive
	 *
	 * @throws ExportsException
	 */
	protected function createGZip(): string
	{
		// Force an extension so DownloadResponse can set the MIME
		$path = tempnam(sys_get_temp_dir(), 'exports-') . '.tar';

		try
		{
			$archive = new PharData($path);
		}
		catch (\UnexpectedValueException $e)
		{
			throw new ExportsException('PharData failed during initialization', $e->getCode(), $e);
    	}

		while ($file = $this->getFile())
		{
			$filePath = $file->getRealPath() ?: (string) $file;

			try
			{
				$archive->addFile($filePath);
			}
			catch (\Throwable $e)
			{
				throw new ExportsException($e->getMessage(), $e->getCode(), $e);					
			}
		}

		try
		{
			$new = $archive->compress(Phar::GZ);
		}
		catch (\Throwable $e)
		{
			throw new ExportsException('PharData compression failed', $e->getCode(), $e);					
		}

		$this->setFileName('Archive-' . time() . '.tar.gz');

		return $new->getPath();
	}
}
