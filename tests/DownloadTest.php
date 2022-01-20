<?php

use CodeIgniter\HTTP\DownloadResponse;
use Tatter\Exports\Exporters\DownloadExporter;
use Tests\Support\TestCase;

/**
 * @internal
 */
final class DownloadTest extends TestCase
{
    public function testProcess()
    {
        $exporter = new DownloadExporter($this->input);

        $response = $exporter->process();
        $this->assertInstanceOf(DownloadResponse::class, $response);

        $result = $this->getPrivateProperty($response, 'filename');
        $this->assertSame(basename($this->input), $result);
    }
}
