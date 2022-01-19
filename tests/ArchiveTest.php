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
        $handler = new ArchiveExporter($this->input);
        $handler->setFormat('zip');

        $response = $handler->process();
        $result   = $this->getPrivateProperty($response, 'file');

        $this->assertInstanceOf(File::class, $result);
        $this->assertStringContainsString('zip', $result->getMimeType());
        $this->assertStringNotContainsString('gzip', $result->getMimeType());
    }

    public function testGZip()
    {
        $handler = new ArchiveExporter($this->input);
        $handler->setFormat('gzip');

        $response = $handler->process();
        $result   = $this->getPrivateProperty($response, 'file');

        $this->assertInstanceOf(File::class, $result);
        $this->assertStringContainsString('gzip', $result->getMimeType());
    }

    public function testMultipleFiles()
    {
        $handler = new ArchiveExporter();

        $handler->setFile(SUPPORTPATH . 'assets/image.jpg');
        $handler->setFile(SUPPORTPATH . 'assets/text.txt');

        $response = $handler->process();
        $result   = $this->getPrivateProperty($response, 'file');

        $this->assertInstanceOf(File::class, $result);
    }
}
