<?php

namespace dbeurive\UtilTest;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'setUp.php';

use dbeurive\Util\UtilUnitTest;

class ClassToTest {

    public $factor = 1;

    static private function __privateStatic($inParam) {
        return 2*$inParam;
    }

    static protected function _protectedStatic($inParam) {
        return 3*$inParam;
    }

    private function __privateNonStatic($inParam) {
        return $this->factor * $inParam;
    }

    protected function _protectedNonStatic($inParam) {
        return $this->factor * $inParam;
    }
}

class UtilUnitTestTest extends \PHPUnit_Framework_TestCase
{
    public function testPrivateStatic() {
        $this->assertEquals(20, UtilUnitTest::call_private_or_protected_static_method('\dbeurive\UtilTest\ClassToTest', '__privateStatic', 10));
    }

    public function testProtectedStatic() {
        $this->assertEquals(30, UtilUnitTest::call_private_or_protected_static_method('\dbeurive\UtilTest\ClassToTest', '_protectedStatic', 10));
    }

    public function testPrivateNonStatic() {
        $o = new ClassToTest();
        $o->factor = 4;
        $this->assertEquals(40, UtilUnitTest::call_private_or_protected_method('\dbeurive\UtilTest\ClassToTest', '__privateNonStatic', $o, 10));
    }

    public function testProtectedNonStatic() {
        $o = new ClassToTest();
        $o->factor = 5;
        $this->assertEquals(50, UtilUnitTest::call_private_or_protected_method('\dbeurive\UtilTest\ClassToTest', '_protectedNonStatic', $o, 10));
    }
}