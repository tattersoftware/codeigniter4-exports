<?php

namespace Tatter\Exports\Factories;

use Tatter\Exports\BaseExporter;
use Tatter\Handlers\BaseFactory;

/**
 * Exporter Factory Class
 *
 * Used to discover all compatible Exporters.
 *
 * @method static class-string<BaseExporter>   find(string $id)
 * @method static class-string<BaseExporter>[] findAll()
 */
final class ExporterFactory extends BaseFactory
{
    public const HANDLER_PATH = 'Exporters';
    public const HANDLER_TYPE = BaseExporter::class;

    /**
     * Gathers attributes for all Exporters that support the given extension.
     *
     * @return array<string, scalar>[]
     */
    public static function getAttributesForExtension(string $extension): array
    {
        $specific  = [];
        $universal = [];

        foreach (self::findAll() as $exporter) {
            $attributes = $exporter::attributes();

            if (in_array($extension, $attributes['extensions'], true)) {
                $specific[] = $attributes;
            } elseif ($attributes['extensions'] === ['*']) {
                $universal[] = $attributes;
            }
        }

        // Always return extension-specific handlers first
        return [...$specific, ...$universal];
    }
}
