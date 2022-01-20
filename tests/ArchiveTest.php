<?php

use CodeIgniter\Files\File;
use Tatter\Exports\Exporters\ArchiveExporter;
use Tests\Support\TestCase;

/**
 * @internal
 */
final class ArchiveTest extends TestCase
{
    public function testZip()
    {
        $exporter = new ArchiveExporter($this->input);
        $exporter->setFormat('zip');

        $response = $exporter->process();
        $result   = $this->getPrivateProperty($response, 'file');

        $this->assertInstanceOf(File::class, $result);
        $this->assertStringContainsString('zip', $result->getMimeType());
        $this->assertStringNotContainsString('gzip', $result->getMimeType());
    }

    public function testGZip()
    {
        $exporter = new ArchiveExporter($this->input);
        $exporter->setFormat('gzip');

        $response = $exporter->process();
        $result   = $this->getPrivateProperty($response, 'file');

        $this->assertInstanceOf(File::class, $result);
        $this->assertStringContainsString('gzip', $result->getMimeType());
    }

    public function testFallback()
    {
        // Create a fake failure in createZip() to verify it falls back on GZip
        $exporter = new class ($this->input) extends ArchiveExporter {
            protected function createZip(): string
            {
                throw new RuntimeException('I get knocked down...');
            }
        };

        $response = $exporter->process();
        $result   = $this->getPrivateProperty($response, 'file');

        $this->assertInstanceOf(File::class, $result);
        $this->assertStringContainsString('gzip', $result->getMimeType());
    }

    public function testMultipleFiles()
    {
        $exporter = new ArchiveExporter();

        $exporter->setFile(SUPPORTPATH . 'assets/image.jpg');
        $exporter->setFile(SUPPORTPATH . 'assets/text.txt');

        $response = $exporter->process();
        $result   = $this->getPrivateProperty($response, 'file');

        $this->assertInstanceOf(File::class, $result);
    }
}
