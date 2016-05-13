<?php

namespace dbeurive\UtilTest;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'setUp.php';

use dbeurive\Util\UtilCode;

class UtilCodeTest extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\UtilTest\SetUp;

    public function setUp() {
        $this->__setUp();
    }

    public function test_require_with_args() {

        $fileToInclude = $this->__dirInputs . DIRECTORY_SEPARATOR . 'test_require_with_args.php';
        $result = UtilCode::require_with_args($fileToInclude, ['parameter' => 15]);
        $this->assertTrue(is_array($result));
        $this->assertCount(1, $result);
        $this->assertEquals(30, $result[0]);
    }
}