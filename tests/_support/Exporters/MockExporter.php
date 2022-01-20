<?php

namespace Tests\Support\Exporters;

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\BaseExporter;

class MockExporter extends BaseExporter
{
    public static function handlerId(): string
    {
        return 'mock';
    }

    public static function attributes(): array
    {
        return [
            'name'       => 'Mock',
            'icon'       => 'fas fa-flask',
            'summary'    => 'Mock export handler',
            'extensions' => '*',
            'ajax'       => true,
            'direct'     => true,
            'bulk'       => true,
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
