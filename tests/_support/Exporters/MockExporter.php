<?php

namespace Tests\Support\Exporters;

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\BaseExporter;

class MockExporter extends BaseExporter
{
    public const HANDLER_ID = 'mock';

    protected static function getAttributes(): array
    {
        return [
            'name'       => 'Mock',
            'icon'       => 'fas fa-flask',
            'summary'    => 'Mock export handler',
            'ajax'       => true,
            'direct'     => true,
            'bulk'       => true,
            'extensions' => ['*'],
        ];
    }

    /**
     * Blindly does nothing.
     */
    protected function doProcess(): ResponseInterface
    {
        return $this->response;
    }
}
