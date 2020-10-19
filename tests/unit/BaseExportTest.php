<?php

use CodeIgniter\Files\File;
use Tatter\Exports\Exports\ImageHandler;
use Tests\Support\ExportsTestCase;
use Tests\Support\Exports\MockExport;

class BaseExportTest extends ExportsTestCase
{
	public function testGetFiles()
	{
		$handler = new MockExport($this->input);
		$result  = $handler->getFiles();
		$this->assertIsArray($result);
		$this->assertCount(1, $result);
	}

	public function testConstructSetsFile()
	{
		$handler = new MockExport($this->input);
		$result  = $handler->getFiles();

		$this->assertInstanceOf(File::class, $result[0]);
	}

	public function testSetFileSetsFile()
	{
		$handler = new MockExport();
		$handler->setFile($this->input);

		$result = $handler->getFiles();

		$this->assertInstanceOf(File::class, $result[0]);
	}

	public function testSetFileAcceptsFile()
	{
		$handler = new MockExport();
		$handler->setFile(new File($this->input));

		$result = $handler->getFiles();

		$this->assertInstanceOf(File::class, $result[0]);
	}

	public function testSetFileName()
	{
		$name = 'foo';

		$handler = new MockExport($this->input);
		$handler->setFileName($name);

		$result = $this->getPrivateProperty($handler, 'fileName');

		$this->assertEquals($name, $result);
	}

	public function testSetFileMime()
	{
		$mime = 'bar';

		$handler = new MockExport($this->input);
		$handler->setFileMime($mime);

		$result = $this->getPrivateProperty($handler, 'fileMime');

		$this->assertEquals($mime, $result);
	}

	public function testProcessGuessesName()
	{
		$handler = new MockExport($this->input);
		$handler->process();

		$result = $this->getPrivateProperty($handler, 'fileName');

		$this->assertEquals('image.jpg', $result);
	}

	public function testProcessGuessesMime()
	{
		$handler = new MockExport($this->input);
		$handler->process();

		$result = $this->getPrivateProperty($handler, 'fileMime');

		$this->assertEquals('image/jpeg', $result);
	}

	public function testGetFile()
	{
		$handler = new MockExport($this->input);
		$result  = $handler->getFile();
		$this->assertInstanceOf(File::class, $result);
		$this->assertEquals($this->input, (string) $result);

		$result = $handler->getFiles();
		$this->assertEquals([], $result);
	}
}
