<?php

namespace Tatter\Exports\Exporters;

use CodeIgniter\HTTP\DownloadResponse;
use Tatter\Exports\BaseExporter;

class DownloadExporter extends BaseExporter
{
    public const HANDLER_ID = 'download';

    protected static function getAttributes(): array
    {
        return [
            'name'       => 'Download',
            'icon'       => 'fas fa-file-download',
            'summary'    => 'Download a file straight from the browser',
            'ajax'       => false,
            'direct'     => true,
            'bulk'       => false,
            'extensions' => ['*'],
        ];
    }

    /**
     * Creates a download response for the browser.
     */
    protected function doProcess(): DownloadResponse
    {
        $file = $this->getFile();
        $path = $file->getRealPath() ?: (string) $file;

        // Create the download response
        return $this->response->download($path, null, true)->setFileName($this->fileName);
    }
}
