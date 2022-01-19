<?php

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\Exporters\PreviewExporter;
use Tests\Support\TestCase;

/**
 * @internal
 */
final class PreviewTest extends TestCase
{
    public function testDirectSetsMime()
    {
        $handler = new PreviewExporter($this->input);
        $result  = $handler->process();

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertSame('image/jpeg', $result->header('Content-Type')->getValue());
    }
}
