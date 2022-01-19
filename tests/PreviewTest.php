<?php

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\Exporters\PreviewExporter;
use Tests\Support\TestCase;

/**
 * @internal
 */
final class PreviewTest extends TestCase
{
    public function testDirect()
    {
        $exporter = new PreviewExporter($this->input);
        $response = $exporter->process();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame('image/jpeg', $response->header('Content-Type')->getValue());
        $this->assertSame(file_get_contents($this->input), $response->getBody());
    }

    public function testAjaxImage()
    {
        $exporter = new PreviewExporter($this->input, $this->getAjaxRequest());

        $response = $exporter->process();
        $this->assertInstanceOf(ResponseInterface::class, $response);

        $result = $response->getBody();
        $this->assertStringContainsString(current_url(), $result);
        $this->assertStringContainsString('<image/jpeg;base64, /9j/4AAQ', $result);
    }

    public function testAjaxPdf()
    {
        $exporter = new PreviewExporter($this->input, $this->getAjaxRequest());
        $exporter->setFileMime('application/pdf');

        $response = $exporter->process();
        $this->assertInstanceOf(ResponseInterface::class, $response);

        $result = $response->getBody();
        $this->assertStringContainsString(current_url(), $result);
        $this->assertStringContainsString('<object data="data:application/pdf;base64,', $result);
    }
}
