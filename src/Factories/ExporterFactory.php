<?php

namespace Tatter\Exports\Factories;

use Tatter\Exports\BaseExporter;
use Tatter\Handlers\BaseFactory;

/**
 * Exporter Factory Class
 *
 * Used to discover all compatible Exporters.
 */
class ExporterFactory extends BaseFactory
{
    public const RETURN_TYPE = BaseExporter::class;

    /**
     * Returns the search path.
     */
    public function getPath(): string
    {
        return 'Exporters';
    }
}
