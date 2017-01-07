<?php

namespace dbeurive\UtilTest;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'SetUp.php';

use dbeurive\Util\UtilArray;

class UtilArrayTest extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\UtilTest\SetUp;

    public function setUp() {
        $this->__setUp();
    }

    public function test_array_keys_exists() {

        $keys   = ['A', 'B', 'C'];
        $array1 = ['A' => 1, 'B' => 2, 'C' => 3];
        $array2 = ['A' => 1, 'B' => 2, 'D' => 3];

        $this->assertTrue(UtilArray::array_keys_exists($keys, $array1));
        $this->assertFalse(UtilArray::array_keys_exists($keys, $array2));
    }

    public function test_array_keep() {

        $array = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4];
        $extracted = UtilArray::array_keep_keys(['A', 'B'], $array);

        $this->assertCount(2, $extracted);
        $this->assertArrayHasKey('A', $extracted);
        $this->assertArrayHasKey('B', $extracted);
        $this->assertEquals(1, $extracted['A']);
        $this->assertEquals(2, $extracted['B']);

        $extracted = UtilArray::array_keep_keys(['A', 'B'], $array, true);
        $this->assertCount(2, $extracted);
        $this->assertEquals(1, $extracted[0]);
        $this->assertEquals(2, $extracted[1]);
    }
}