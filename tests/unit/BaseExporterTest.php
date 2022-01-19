<?php

use CodeIgniter\Files\File;
use Tests\Support\Exporters\MockExporter;
use Tests\Support\ExportsTestCase;

/**
 * @internal
 */
final class BaseExporterTest extends ExportsTestCase
{
    public function testGetFiles()
    {
        $handler = new MockExporter($this->input);
        $result  = $handler->getFiles();
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    public function testConstructSetsFile()
    {
        $handler = new MockExporter($this->input);
        $result  = $handler->getFiles();

        $this->assertInstanceOf(File::class, $result[0]);
    }

    public function testSetFileSetsFile()
    {
        $handler = new MockExporter();
        $handler->setFile($this->input);

        $result = $handler->getFiles();

        $this->assertInstanceOf(File::class, $result[0]);
    }

    public function testSetFileAcceptsFile()
    {
        $handler = new MockExporter();
        $handler->setFile(new File($this->input));

        $result = $handler->getFiles();

        $this->assertInstanceOf(File::class, $result[0]);
    }

    public function testSetFileName()
    {
        $name = 'foo';

        $handler = new MockExporter($this->input);
        $handler->setFileName($name);

        $result = $this->getPrivateProperty($handler, 'fileName');

        $this->assertSame($name, $result);
    }

    public function testSetFileMime()
    {
        $mime = 'bar';

        $handler = new MockExporter($this->input);
        $handler->setFileMime($mime);

        $result = $this->getPrivateProperty($handler, 'fileMime');

        $this->assertSame($mime, $result);
    }

    public function testProcessGuessesName()
    {
        $handler = new MockExporter($this->input);
        $handler->process();

        $result = $this->getPrivateProperty($handler, 'fileName');

        $this->assertSame('image.jpg', $result);
    }

    public function testProcessGuessesMime()
    {
        $handler = new MockExporter($this->input);
        $handler->process();

        $result = $this->getPrivateProperty($handler, 'fileMime');

        $this->assertSame('image/jpeg', $result);
    }

    public function testGetFile()
    {
        $handler = new MockExporter($this->input);
        $result  = $handler->getFile();
        $this->assertInstanceOf(File::class, $result);
        $this->assertSame($this->input, (string) $result);

        $result = $handler->getFiles();
        $this->assertSame([], $result);
    }
}
