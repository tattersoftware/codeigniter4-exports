<?php

namespace Tests\Support;

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
abstract class TestCase extends CIUnitTestCase
{
    /**
     * Path to the test file
     *
     * @var string
     */
    protected $input = SUPPORTPATH . 'assets/image.jpg';

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->resetServices();
    }

    protected function getAjaxRequest(): IncomingRequest
    {
        return service('request')->appendHeader('X-Requested-With', 'XMLHttpRequest');
    }
}
