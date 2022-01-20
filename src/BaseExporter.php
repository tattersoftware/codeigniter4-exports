<?php

namespace Tatter\Exports;

use CodeIgniter\Config\Factories;
use CodeIgniter\Events\Events;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\Exceptions\ExportsException;
use Tatter\Handlers\Interfaces\HandlerInterface;

abstract class BaseExporter implements HandlerInterface
{
    /**
     * Array of Files to export.
     *
     * @var File[]
     */
    private array $files = [];

    /**
     * Alternate name to use for the file.
     */
    protected ?string $fileName;

    /**
     * Overriding MIME type to use.
     */
    protected ?string $fileMime;

    /**
     * Instance of the main Request object.
     */
    protected RequestInterface $request;

    /**
     * Instance of the main response object.
     */
    protected ResponseInterface $response;

    /**
     * Use Factories-style class basenames to
     * guesstimate a good handlerId.
     */
    public static function handlerId(): string
    {
        return str_replace('export', '', strtolower(Factories::getBasename(static::class)));
    }

    /**
     * Initial set of attributes, to be overridden
     * as necessary by child classes.
     */
    public static function attributes(): array
    {
        return [
            'name'       => ucfirst(static::handlerId()),
            'icon'       => 'fas fa-external-link-alt',
            'summary'    => '',
            'extensions' => '*',
            'ajax'       => false,
            'direct'     => true,
            'bulk'       => false,
        ];
    }

    /**
     * Sets or loads the Request and Response objects.
     *
     * @param File|string|null  $file
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    public function __construct($file = null, ?RequestInterface $request = null, ?ResponseInterface $response = null)
    {
        $this->setFile($file);

        $this->request  = $request ?? service('request');
        $this->response = $response ?? service('response');
    }

    //--------------------------------------------------------------------

    /**
     * Runs this Export process.
     */
    abstract protected function doProcess(): ?ResponseInterface;

    /**
     * Wrapper for child process method.
     */
    public function process(): ?ResponseInterface
    {
        if (empty($this->files)) {
            throw new ExportsException(lang('Exports.noFiles'));
        }
        $file = reset($this->files);

        // If no file name was specified then set it to the base name
        $this->fileName ??= $file->getBasename();

        // If no MIME was specified then read it from the file
        $this->fileMime ??= $file->getMimeType();

        // Trigger an Export event
        Events::trigger('export', [
            'handlerId' => static::handlerId(),
            'handler'   => static::attributes(),
            'file'      => $file->getRealPath() ?: (string) $file,
            'fileName'  => $this->fileName,
            'fileMime'  => $this->fileMime,
        ]);

        return $this->doProcess();
    }

    //--------------------------------------------------------------------

    /**
     * Gets the next File, shortening the array.
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

    /**
     * Set the target File, or append a file for bulk handlers.
     *
     * @param File|string|null $file The File or path to export, null to reset
     *
     * @throws FileNotFoundException If supplied a path to a non-existant file
     */
    public function setFile($file): self
    {
        if (is_string($file)) {
            $file = new File($file, true);
        }

        if (null === $file) {
            $this->files = [];
        } elseif (static::attributes()['bulk']) {
            $this->files[] = $file;
        } else {
            $this->files = [$file];
        }

        return $this;
    }

    /**
     * Set the alternate file name.
     */
    public function setFileName(?string $fileName = null): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Set the MIME type.
     */
    public function setFileMime(?string $fileMime = null): self
    {
        $this->fileMime = $fileMime;

        return $this;
    }
}
