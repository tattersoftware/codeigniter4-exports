<?php

namespace Tatter\Exports\Exports;

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\BaseExport;

class ImageHandler extends BaseExport
{
    /**
     * Attributes for Tatter\Handlers
     *
     * @var array<string, mixed>
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
     */
    protected function _process(): ?ResponseInterface
    {
        return $this->request->isAJAX()
            ? $this->processAJAX()
            : $this->processDirect();
    }

    /**
     * Reads the file out directly to browser.
     */
    protected function processDirect(): ResponseInterface
    {
        $file = $this->getFile();
        $path = $file->getRealPath() ?: (string) $file;

        // Set the headers and read out the file
        return $this->response
            ->setHeader('Content-Type', $this->fileMime)
            ->setBody(file_get_contents($path));
    }

    /**
     * Creates an appropriate HTML tag.
     */
    protected function processAJAX(): ResponseInterface
    {
        $file = $this->getFile();
        $path = $file->getRealPath() ?: (string) $file;

        return $this->response->setBody(view('\Tatter\Exports\Views\image', [
            'fileName' => $this->fileName,
            'fileMime' => $this->fileMime,
            'data'     => base64_encode(file_get_contents($path)),
        ]));
    }
}
