<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
abstract class ExportsTestCase extends CIUnitTestCase
{
    /**
     * Path to the test file
     *
     * @var string
     */
    protected $input = SUPPORTPATH . 'assets/image.jpg';
}
