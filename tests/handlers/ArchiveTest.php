<?php

use CodeIgniter\Files\File;
use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\Exports\ArchiveHandler;
use Tests\Support\ExportsTestCase;

class ArchiveTest extends ExportsTestCase
{
	public function testZip()
	{
		$handler = new ArchiveHandler($this->input);
		$handler->setFormat('zip');

		$response = $handler->process();
		$result   = $this->getPrivateProperty($response, 'file');

		$this->assertInstanceOf(File::class, $result);
		$this->assertStringContainsString('zip', $result->getMimeType());
		$this->assertStringNotContainsString('gzip', $result->getMimeType());
	}

	public function testGZip()
	{
		$handler = new ArchiveHandler($this->input);
		$handler->setFormat('gzip');

		$response = $handler->process();
		$result   = $this->getPrivateProperty($response, 'file');

		$this->assertInstanceOf(File::class, $result);
		$this->assertStringContainsString('gzip', $result->getMimeType());
	}

	public function testMultipleFiles()
	{
		$handler = new ArchiveHandler();
		
		$handler->setFile(SUPPORTPATH . 'assets/image.jpg');
		$handler->setFile(SUPPORTPATH . 'assets/text.txt');

		$response = $handler->process();
		$result   = $this->getPrivateProperty($response, 'file');

		$this->assertInstanceOf(File::class, $result);
	}
}
