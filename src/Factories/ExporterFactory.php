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

    /**
     * Returns attributes for all Exporters that support the given extension.
     *
     * @return array<string, scalar>[]
     */
    public function getAttributesForExtension(string $extension): array
    {
        // Always return extension-specific handlers first
        $exporters = array_merge(
            $this->where(['extensions has' => $extension])->findAll(),
            $this->where(['extensions' => '*'])->findAll()
        );

        // Gather each set of attributes
        $result = [];

        foreach ($exporters as $exporter) {
            $handlerId = $exporter::handlerId();
            $result[]  = $this->getAttributes($handlerId);
        }

        return $result;
    }
}
