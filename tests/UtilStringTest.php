<?php

namespace dbeurive\UtilTest;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'SetUp.php';

use dbeurive\Util\UtilString;

class UtilStringTest extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\UtilTest\SetUp;

    public function setUp() {
        $this->__setUp();
    }

    function test_trim() {

        // Trim from the end.
        $inputText = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL";
        $expected  = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL";
        $this->assertEquals($expected, UtilString::trim($inputText));

        $inputText = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL  \n";
        $expected  = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL";
        $this->assertEquals($expected, UtilString::trim($inputText));

        // Trim from the beginning.
        $inputText = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL";
        $expected  = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL";
        $this->assertEquals($expected, UtilString::trim($inputText, UtilString::TRIM_BEGINNING));

        $inputText = "  \r\n  ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL  \n";
        $expected  = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL  \n";
        $this->assertEquals($expected, UtilString::trim($inputText, UtilString::TRIM_BEGINNING));

        // Trim from both the beginning and the end.
        $inputText = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL";
        $expected  = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL";
        $this->assertEquals($expected, UtilString::trim($inputText, UtilString::TRIM_BOTH));

        $inputText = "  \r\n  ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL  \n";
        $expected  = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL";
        $this->assertEquals($expected, UtilString::trim($inputText, UtilString::TRIM_BOTH));
    }

    function test_text_linearize() {
        $inputText = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL  \n";
        $expected  = "ABC      DEF GHI  JKL   ";
        $this->assertEquals($expected, UtilString::text_linearize($inputText));

        $inputText = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL  \n";
        $expected  = "ABC DEF GHI JKL ";
        $this->assertEquals($expected, UtilString::text_linearize($inputText, true));

        $inputText = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL  \n";
        $expected  = "ABC DEF GHI JKL";
        $this->assertEquals($expected, UtilString::text_linearize($inputText, true, true));

        $inputText = "ABC  \n\n  DEF\r\nGHI\r\n\r\nJKL  \n\r\n  ";
        $expected  = "ABC DEF GHI JKL";
        $this->assertEquals($expected, UtilString::text_linearize($inputText, true, true));
    }


}