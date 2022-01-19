<?php

namespace Tatter\Exports\Factories;

use Tatter\Exports\BaseExport;
use Tatter\Handlers\BaseFactory;

/**
 * Thumbnailer Factory Class
 *
 * Used to discover all compatible Thumbnailers.
 */
class ExportFactory extends BaseFactory
{
    public const RETURN_TYPE = BaseExport::class;

    /**
     * Returns the search path.
     */
    public function getPath(): string
    {
        return 'Exports';
    }
}
