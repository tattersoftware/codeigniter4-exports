<?php

namespace Tatter\Exports\Exports;

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\BaseExport;

class DownloadHandler extends BaseExport
{
    public static function handlerId(): string
    {
        return 'download';
    }

    public static function attributes(): array
    {
        return [
            'name'       => 'Download',
            'icon'       => 'fas fa-file-download',
            'summary'    => 'Download a file straight from the browser',
            'extensions' => '*',
            'ajax'       => false,
            'direct'     => true,
            'bulk'       => false,
        ];
    }

    /**
     * Creates a download response for the browser.
     */
    protected function doProcess(): ResponseInterface
    {
        $file = $this->getFile();
        $path = $file->getRealPath() ?: (string) $file;

        // Create the download response
        return $this->response->download($path, null, true)->setFileName($this->fileName);
    }
}
