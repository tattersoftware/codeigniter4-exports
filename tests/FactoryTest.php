<?php

namespace Tatter\Exports\Exporters;

use Tatter\Exports\Factories\ExporterFactory;
use Tests\Support\Exporters\MockExporter;
use Tests\Support\TestCase;

/**
 * @internal
 */
final class FactoryTest extends TestCase
{
    public function testDiscovers()
    {
        // Discovery is alphabetical by handlerId
        $expected = [
            ArchiveExporter::class,
            DownloadExporter::class,
            MockExporter::class,
            PreviewExporter::class,
        ];

        $factory = new ExporterFactory();
        $result  = $factory->findAll();

        $this->assertSame($expected, $result);
    }
}
