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
        // Discovery is alphabetical by handler ID
        $expected = [
            'archive'  => ArchiveExporter::class,
            'download' => DownloadExporter::class,
            'mock'     => MockExporter::class,
            'preview'  => PreviewExporter::class,
        ];

        $factory = new ExporterFactory();
        $result  = $factory->findAll();

        $this->assertSame($expected, $result);
    }

    public function testGetAttributesForExtension()
    {
        $factory = new ExporterFactory();

        $result = $factory->getAttributesForExtension('jpg');
        $this->assertCount(4, $result);
        $this->assertSame(
            ['preview', 'archive', 'download', 'mock'],
            array_column($result, 'id'),
        );

        $result = $factory->getAttributesForExtension('app');
        $this->assertCount(3, $result);
        $this->assertSame(
            ['archive', 'download', 'mock'],
            array_column($result, 'id'),
        );
    }
}
