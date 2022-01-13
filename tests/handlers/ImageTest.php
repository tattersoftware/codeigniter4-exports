<?php

use CodeIgniter\HTTP\ResponseInterface;
use Tatter\Exports\Exports\ImageHandler;
use Tests\Support\ExportsTestCase;

/**
 * @internal
 */
final class ImageTest extends ExportsTestCase
{
    public function testDirectSetsMime()
    {
        $handler = new ImageHandler($this->input);
        $result  = $handler->process();

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertSame('image/jpeg', $result->header('Content-Type')->getValue());
    }
}
