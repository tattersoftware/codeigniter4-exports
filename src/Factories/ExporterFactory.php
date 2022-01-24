<?php

namespace Tatter\Exports\Factories;

use Tatter\Exports\BaseExporter;
use Tatter\Handlers\BaseFactory;

/**
 * Exporter Factory Class
 *
 * Used to discover all compatible Exporters.
 *
 * @method class-string<BaseExporter>|null   find(string $handlerId)
 * @method class-string<BaseExporter>[]|null findAll()
 * @method class-string<BaseExporter>|null   first()
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
