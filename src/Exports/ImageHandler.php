<?php

namespace Tatter\Exports\Exports;

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\BaseExport;

class ImageHandler extends BaseExport
{
    public static function handlerId(): string
    {
        return 'preview';
    }

    public static function attributes(): array
    {
        return [
            'name'       => 'Preview',
            'icon'       => 'fas fa-image',
            'summary'    => 'Open an image in the browser',
            'extensions' => 'jpg,jpeg,gif,png,pdf,bmp,ico',
            'ajax'       => true,
            'direct'     => true,
            'bulk'       => false,
        ];
    }

    /**
     * Checks for AJAX to tag image, otherwise reads out the file directly.
     */
    protected function doProcess(): ?ResponseInterface
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
        $data = base64_encode(file_get_contents($path));
        $body = '<a href="' . current_url() . '" target="_blank" style="cursor: zoom-in;">' . PHP_EOL;

        if ($this->fileMime === 'application/pdf') {
            $body .= '<object data="data:application/pdf;base64, ' . $data . '" type="application/pdf"></object>' . PHP_EOL;
        } else {
            $body .= '<img src="data:<' . $this->fileMime . ';base64, ' . $data . '" alt="' . $this->fileName . '">' . PHP_EOL;
        }
        $body .= '</a>' . PHP_EOL;

        return $this->response->setBody($body);
    }
}
