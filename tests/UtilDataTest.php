<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'setUp.php';

use dbeurive\Util\UtilData;

class UtilDataTest extends PHPUnit_Framework_TestCase
{
    use SetUp;

    public function setUp() {
        $this->__setUp();
    }

    public function test_to_callable_php_file() {

        $data = array('A', 'B', 'C');

        $targetFile = $this->__dirData . DIRECTORY_SEPARATOR . "callable_php_file.php";
        $referenceFile = $this->__dirReferences . DIRECTORY_SEPARATOR . "callable_php_file.php";
        UtilData::to_callable_php_file($data, $targetFile);
        $this->assertFileEquals($targetFile, $referenceFile);

        $string = UtilData::to_callable_php_file($data);
        $this->assertStringEqualsFile($this->__dirReferences . DIRECTORY_SEPARATOR . "callable_php_file.php", $string);
    }
}