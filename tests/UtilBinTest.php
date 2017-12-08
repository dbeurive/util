<?php

namespace dbeurive\UtilTest;
use dbeurive\Util\UtilBin;

class UtilBinTest  extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\UtilTest\SetUp;

    public function setUp() {
        $this->__setUp();
    }

    private function __negateUnsignedChar($inUnsignedChar) {
        return -1 * ((0xFF ^ $inUnsignedChar) + 1);
    }

    // ------------------------------------------------------------
    // Type "c" (8 bits)
    // ------------------------------------------------------------

    public function test_unpack_c() {

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('c4f1/c4f2/c4f3/c4f4', $input, false);
        $this->assertEquals(16, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertArrayHasKey('f3', $data);
        $this->assertArrayHasKey('f4', $data);
        $this->assertCount(4, $data['f1']);
        $this->assertCount(4, $data['f2']);
        $this->assertCount(4, $data['f3']);
        $this->assertCount(4, $data['f4']);
        $this->assertEquals($this->__negateUnsignedChar(0xAA), $data['f1'][1]);
        $this->assertEquals($this->__negateUnsignedChar(0xBB), $data['f1'][2]);
        $this->assertEquals($this->__negateUnsignedChar(0xCC), $data['f1'][3]);
        $this->assertEquals($this->__negateUnsignedChar(0xDD), $data['f1'][4]);
        $this->assertEquals(0x11, $data['f2'][1]);
        $this->assertEquals(0x22, $data['f2'][2]);
        $this->assertEquals(0x33, $data['f2'][3]);
        $this->assertEquals(0x44, $data['f2'][4]);
        $this->assertEquals(0x55, $data['f3'][1]);
        $this->assertEquals(0x66, $data['f3'][2]);
        $this->assertEquals(0x77, $data['f3'][3]);
        $this->assertEquals($this->__negateUnsignedChar(0x88), $data['f3'][4]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][1]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][2]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][3]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][4]);

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('c4f1/c4f2/c4f3/c2f4', $input, false);
        $this->assertEquals(16, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertArrayHasKey('f3', $data);
        $this->assertArrayHasKey('f4', $data);
        $this->assertCount(4, $data['f1']);
        $this->assertCount(4, $data['f2']);
        $this->assertCount(4, $data['f3']);
        $this->assertCount(2, $data['f4']);
        $this->assertEquals($this->__negateUnsignedChar(0xAA), $data['f1'][1]);
        $this->assertEquals($this->__negateUnsignedChar(0xBB), $data['f1'][2]);
        $this->assertEquals($this->__negateUnsignedChar(0xCC), $data['f1'][3]);
        $this->assertEquals($this->__negateUnsignedChar(0xDD), $data['f1'][4]);
        $this->assertEquals(0x11, $data['f2'][1]);
        $this->assertEquals(0x22, $data['f2'][2]);
        $this->assertEquals(0x33, $data['f2'][3]);
        $this->assertEquals(0x44, $data['f2'][4]);
        $this->assertEquals(0x55, $data['f3'][1]);
        $this->assertEquals(0x66, $data['f3'][2]);
        $this->assertEquals(0x77, $data['f3'][3]);
        $this->assertEquals($this->__negateUnsignedChar(0x88), $data['f3'][4]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][1]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][2]);

        $input = pack('c1', 0xAA);
        $data = UtilBin::unpack('c1f', $input, false);
        $this->assertEquals(1, strlen($input));
        $this->assertArrayHasKey('f', $data);
        $this->assertCount(1, $data['f']);
        $this->assertEquals($this->__negateUnsignedChar(0xAA), $data['f'][1]);
    }

    public function test_unpack_truncate_c() {

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('c4f1/c4f2/c4f3/c4f4', $input);
        $this->assertEquals(0, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertArrayHasKey('f3', $data);
        $this->assertArrayHasKey('f4', $data);
        $this->assertCount(4, $data['f1']);
        $this->assertCount(4, $data['f2']);
        $this->assertCount(4, $data['f3']);
        $this->assertCount(4, $data['f4']);
        $this->assertEquals($this->__negateUnsignedChar(0xAA), $data['f1'][1]);
        $this->assertEquals($this->__negateUnsignedChar(0xBB), $data['f1'][2]);
        $this->assertEquals($this->__negateUnsignedChar(0xCC), $data['f1'][3]);
        $this->assertEquals($this->__negateUnsignedChar(0xDD), $data['f1'][4]);
        $this->assertEquals(0x11, $data['f2'][1]);
        $this->assertEquals(0x22, $data['f2'][2]);
        $this->assertEquals(0x33, $data['f2'][3]);
        $this->assertEquals(0x44, $data['f2'][4]);
        $this->assertEquals(0x55, $data['f3'][1]);
        $this->assertEquals(0x66, $data['f3'][2]);
        $this->assertEquals(0x77, $data['f3'][3]);
        $this->assertEquals($this->__negateUnsignedChar(0x88), $data['f3'][4]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][1]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][2]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][3]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][4]);

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('c4f1/c4f2/c4f3/c2f4', $input);
        $this->assertEquals(2, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertArrayHasKey('f3', $data);
        $this->assertArrayHasKey('f4', $data);
        $this->assertCount(4, $data['f1']);
        $this->assertCount(4, $data['f2']);
        $this->assertCount(4, $data['f3']);
        $this->assertCount(2, $data['f4']);
        $this->assertEquals($this->__negateUnsignedChar(0xAA), $data['f1'][1]);
        $this->assertEquals($this->__negateUnsignedChar(0xBB), $data['f1'][2]);
        $this->assertEquals($this->__negateUnsignedChar(0xCC), $data['f1'][3]);
        $this->assertEquals($this->__negateUnsignedChar(0xDD), $data['f1'][4]);
        $this->assertEquals(0x11, $data['f2'][1]);
        $this->assertEquals(0x22, $data['f2'][2]);
        $this->assertEquals(0x33, $data['f2'][3]);
        $this->assertEquals(0x44, $data['f2'][4]);
        $this->assertEquals(0x55, $data['f3'][1]);
        $this->assertEquals(0x66, $data['f3'][2]);
        $this->assertEquals(0x77, $data['f3'][3]);
        $this->assertEquals($this->__negateUnsignedChar(0x88), $data['f3'][4]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][1]);
        $this->assertEquals($this->__negateUnsignedChar(0xFF), $data['f4'][2]);

        $input = pack('c1', 0xAA);
        $data = UtilBin::unpack('c1f', $input);
        $this->assertEquals(0, strlen($input));
        $this->assertArrayHasKey('f', $data);
        $this->assertCount(1, $data['f']);
        $this->assertEquals($this->__negateUnsignedChar(0xAA), $data['f'][1]);
    }

    // ------------------------------------------------------------
    // Type "C" (8 bits)
    // ------------------------------------------------------------

    public function test_unpack_UC() {

        $input = pack('NN', 0xAABBCCDD, 0x11223344);
        $data = UtilBin::unpack('C4f1/C4f2', $input, false);
        $this->assertEquals(8, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertCount(4, $data['f1']);
        $this->assertCount(4, $data['f2']);
        $this->assertEquals(0xAA, $data['f1'][1]);
        $this->assertEquals(0xBB, $data['f1'][2]);
        $this->assertEquals(0xCC, $data['f1'][3]);
        $this->assertEquals(0xDD, $data['f1'][4]);
        $this->assertEquals(0x11, $data['f2'][1]);
        $this->assertEquals(0x22, $data['f2'][2]);
        $this->assertEquals(0x33, $data['f2'][3]);
        $this->assertEquals(0x44, $data['f2'][4]);

        $input = pack('NN', 0xAABBCCDD, 0x11223344);
        $data = UtilBin::unpack('C4f1/C2f2', $input, false);
        $this->assertEquals(8, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertCount(4, $data['f1']);
        $this->assertCount(2, $data['f2']);
        $this->assertEquals(0xAA, $data['f1'][1]);
        $this->assertEquals(0xBB, $data['f1'][2]);
        $this->assertEquals(0xCC, $data['f1'][3]);
        $this->assertEquals(0xDD, $data['f1'][4]);
        $this->assertEquals(0x11, $data['f2'][1]);
        $this->assertEquals(0x22, $data['f2'][2]);

        $input = pack('C1', 0xAA);
        $data = UtilBin::unpack('C1f', $input, false);
        $this->assertEquals(1, strlen($input));
        $this->assertArrayHasKey('f', $data);
        $this->assertCount(1, $data['f']);
        $this->assertEquals(0xAA, $data['f'][1]);
    }

    public function test_unpack_truncate_UC() {

        $input = pack('NN', 0xAABBCCDD, 0x11223344);
        $data = UtilBin::unpack('C4f1/C4f2', $input);
        $this->assertEquals(0, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertCount(4, $data['f1']);
        $this->assertCount(4, $data['f2']);
        $this->assertEquals(0xAA, $data['f1'][1]);
        $this->assertEquals(0xBB, $data['f1'][2]);
        $this->assertEquals(0xCC, $data['f1'][3]);
        $this->assertEquals(0xDD, $data['f1'][4]);
        $this->assertEquals(0x11, $data['f2'][1]);
        $this->assertEquals(0x22, $data['f2'][2]);
        $this->assertEquals(0x33, $data['f2'][3]);
        $this->assertEquals(0x44, $data['f2'][4]);

        $input = pack('NN', 0xAABBCCDD, 0x11223344);
        $data = UtilBin::unpack('C4f1/C2f2', $input);
        $this->assertEquals(2, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertCount(4, $data['f1']);
        $this->assertCount(2, $data['f2']);
        $this->assertEquals(0xAA, $data['f1'][1]);
        $this->assertEquals(0xBB, $data['f1'][2]);
        $this->assertEquals(0xCC, $data['f1'][3]);
        $this->assertEquals(0xDD, $data['f1'][4]);
        $this->assertEquals(0x11, $data['f2'][1]);
        $this->assertEquals(0x22, $data['f2'][2]);

        $input = pack('C1', 0xAA);
        $data = UtilBin::unpack('C1f', $input);
        $this->assertEquals(0, strlen($input));
        $this->assertArrayHasKey('f', $data);
        $this->assertCount(1, $data['f']);
        $this->assertEquals(0xAA, $data['f'][1]);
    }

    // ------------------------------------------------------------
    // Type "n" (16 bits)
    // ------------------------------------------------------------

    public function test_unpack_n() {

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('n2f1/n2f2/n2f3/n2f4', $input, false);
        $this->assertEquals(16, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertArrayHasKey('f3', $data);
        $this->assertArrayHasKey('f4', $data);
        $this->assertCount(2, $data['f1']);
        $this->assertCount(2, $data['f2']);
        $this->assertCount(2, $data['f3']);
        $this->assertCount(2, $data['f4']);
        $this->assertEquals(0xAABB, $data['f1'][1]);
        $this->assertEquals(0xCCDD, $data['f1'][2]);
        $this->assertEquals(0x1122, $data['f2'][1]);
        $this->assertEquals(0x3344, $data['f2'][2]);
        $this->assertEquals(0x5566, $data['f3'][1]);
        $this->assertEquals(0x7788, $data['f3'][2]);
        $this->assertEquals(0xFFFF, $data['f4'][1]);
        $this->assertEquals(0xFFFF, $data['f4'][2]);

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('n2f1/n2f2/n2f3/n1f4', $input, false);
        $this->assertEquals(16, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertArrayHasKey('f3', $data);
        $this->assertArrayHasKey('f4', $data);
        $this->assertCount(2, $data['f1']);
        $this->assertCount(2, $data['f2']);
        $this->assertCount(2, $data['f3']);
        $this->assertCount(1, $data['f4']);
        $this->assertEquals(0xAABB, $data['f1'][1]);
        $this->assertEquals(0xCCDD, $data['f1'][2]);
        $this->assertEquals(0x1122, $data['f2'][1]);
        $this->assertEquals(0x3344, $data['f2'][2]);
        $this->assertEquals(0x5566, $data['f3'][1]);
        $this->assertEquals(0x7788, $data['f3'][2]);
        $this->assertEquals(0xFFFF, $data['f4'][1]);

        $input = pack('n1', 0xAABB);
        $data = UtilBin::unpack('n1f', $input, false);
        $this->assertEquals(2, strlen($input));
        $this->assertArrayHasKey('f', $data);
        $this->assertCount(1, $data['f']);
        $this->assertEquals(0xAABB, $data['f'][1]);
    }

    public function test_unpack_truncate_n() {

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('n2f1/n2f2/n2f3/n2f4', $input);
        $this->assertEquals(0, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertArrayHasKey('f3', $data);
        $this->assertArrayHasKey('f4', $data);
        $this->assertCount(2, $data['f1']);
        $this->assertCount(2, $data['f2']);
        $this->assertCount(2, $data['f3']);
        $this->assertCount(2, $data['f4']);
        $this->assertEquals(0xAABB, $data['f1'][1]);
        $this->assertEquals(0xCCDD, $data['f1'][2]);
        $this->assertEquals(0x1122, $data['f2'][1]);
        $this->assertEquals(0x3344, $data['f2'][2]);
        $this->assertEquals(0x5566, $data['f3'][1]);
        $this->assertEquals(0x7788, $data['f3'][2]);
        $this->assertEquals(0xFFFF, $data['f4'][1]);
        $this->assertEquals(0xFFFF, $data['f4'][2]);

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('n2f1/n2f2/n2f3/n1f4', $input);
        $this->assertEquals(2, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertArrayHasKey('f3', $data);
        $this->assertArrayHasKey('f4', $data);
        $this->assertCount(2, $data['f1']);
        $this->assertCount(2, $data['f2']);
        $this->assertCount(2, $data['f3']);
        $this->assertCount(1, $data['f4']);
        $this->assertEquals(0xAABB, $data['f1'][1]);
        $this->assertEquals(0xCCDD, $data['f1'][2]);
        $this->assertEquals(0x1122, $data['f2'][1]);
        $this->assertEquals(0x3344, $data['f2'][2]);
        $this->assertEquals(0x5566, $data['f3'][1]);
        $this->assertEquals(0x7788, $data['f3'][2]);
        $this->assertEquals(0xFFFF, $data['f4'][1]);

        $input = pack('n1', 0xAABB);
        $data = UtilBin::unpack('n1f', $input);
        $this->assertEquals(0, strlen($input));
        $this->assertArrayHasKey('f', $data);
        $this->assertCount(1, $data['f']);
        $this->assertEquals(0xAABB, $data['f'][1]);
    }

    // ------------------------------------------------------------
    // Type "N" (32 bites)
    // ------------------------------------------------------------

    public function test_unpack_UN() {

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('N2f1/N2f2', $input, false);
        $this->assertEquals(16, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertCount(2, $data['f1']);
        $this->assertCount(2, $data['f2']);

        if (PHP_INT_SIZE == 8) {
            $this->assertEquals(0xAABBCCDD, $data['f1'][1]);
            $this->assertEquals(0x11223344, $data['f1'][2]);
            $this->assertEquals(0x55667788, $data['f2'][1]);
            $this->assertEquals(0xFFFFFFFF, $data['f2'][2]);
        } else {
            $this->assertEquals('0xAABBCCDD', '0x' . strtoupper(dechex($data['f1'][1])));
            $this->assertEquals('0x11223344', '0x' . strtoupper(dechex($data['f1'][2])));
            $this->assertEquals('0x55667788', '0x' . strtoupper(dechex($data['f2'][1])));
            $this->assertEquals('0xFFFFFFFF', '0x' . strtoupper(dechex($data['f2'][2])));
        }

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('N2f1/N1f2', $input, false);
        $this->assertEquals(16, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertCount(2, $data['f1']);
        $this->assertCount(1, $data['f2']);

        if (PHP_INT_SIZE == 8) {
            $this->assertEquals(0xAABBCCDD, $data['f1'][1]);
            $this->assertEquals(0x11223344, $data['f1'][2]);
            $this->assertEquals(0x55667788, $data['f2'][1]);

        } else {
            $this->assertEquals('0xAABBCCDD', '0x' . strtoupper(dechex($data['f1'][1])));
            $this->assertEquals('0x11223344', '0x' . strtoupper(dechex($data['f1'][2])));
            $this->assertEquals('0x55667788', '0x' . strtoupper(dechex($data['f2'][1])));
        }

        $input = pack('N1', 0xAABBCCDD);
        $data = UtilBin::unpack('N1f', $input, false);
        $this->assertEquals(4, strlen($input));
        $this->assertArrayHasKey('f', $data);
        $this->assertCount(1, $data['f']);
        if (version_compare(phpversion(), '7.0.0', '>')) {
            $this->assertEquals(0xAABBCCDD, $data['f'][1]);
        } else {
            $this->assertEquals('0xAABBCCDD', '0x' . strtoupper(dechex($data['f'][1])));
        }
    }

    public function test_unpack_trucate_UN() {

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('N2f1/N2f2', $input);
        $this->assertEquals(0, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertCount(2, $data['f1']);
        $this->assertCount(2, $data['f2']);

        if (PHP_INT_SIZE == 8) {
            $this->assertEquals(0xAABBCCDD, $data['f1'][1]);
            $this->assertEquals(0x11223344, $data['f1'][2]);
            $this->assertEquals(0x55667788, $data['f2'][1]);
            $this->assertEquals(0xFFFFFFFF, $data['f2'][2]);
        } else {
            $this->assertEquals('0xAABBCCDD', '0x' . strtoupper(dechex($data['f1'][1])));
            $this->assertEquals('0x11223344', '0x' . strtoupper(dechex($data['f1'][2])));
            $this->assertEquals('0x55667788', '0x' . strtoupper(dechex($data['f2'][1])));
            $this->assertEquals('0xFFFFFFFF', '0x' . strtoupper(dechex($data['f2'][2])));
        }

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('N2f1/N1f2', $input);
        $this->assertEquals(4, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertCount(2, $data['f1']);
        $this->assertCount(1, $data['f2']);

        if (PHP_INT_SIZE == 8) {
            $this->assertEquals(0xAABBCCDD, $data['f1'][1]);
            $this->assertEquals(0x11223344, $data['f1'][2]);
            $this->assertEquals(0x55667788, $data['f2'][1]);
        } else {
            $this->assertEquals('0xAABBCCDD', '0x' . strtoupper(dechex($data['f1'][1])));
            $this->assertEquals('0x11223344', '0x' . strtoupper(dechex($data['f1'][2])));
            $this->assertEquals('0x55667788', '0x' . strtoupper(dechex($data['f2'][1])));
        }

        $input = pack('N1', 0xAABBCCDD);
        $data = UtilBin::unpack('N1f', $input);
        $this->assertEquals(0, strlen($input));
        $this->assertArrayHasKey('f', $data);
        $this->assertCount(1, $data['f']);
        if (version_compare(phpversion(), '7.0.0', '>')) {
            $this->assertEquals(0xAABBCCDD, $data['f'][1]);
        } else {
            $this->assertEquals('0xAABBCCDD', '0x' . strtoupper(dechex($data['f'][1])));
        }
    }

    // ------------------------------------------------------------
    // Type "J" (64 bits)
    // ------------------------------------------------------------

    public function test_unpack_J() {

        if (PHP_INT_SIZE == 4) {
            return;
        }

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('J1f1/J1f2', $input, false);
        $this->assertEquals(16, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertCount(1, $data['f1']);
        $this->assertCount(1, $data['f2']);

        $this->assertEquals('0xAABBCCDD11223344', '0x' . strtoupper(dechex($data['f1'][1])));
        $this->assertEquals('0x55667788FFFFFFFF', '0x' . strtoupper(dechex($data['f2'][1])));

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('J1f1', $input, false);
        $this->assertEquals(16, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertCount(1, $data['f1']);
        $this->assertEquals('0xAABBCCDD11223344', '0x' . strtoupper(dechex($data['f1'][1])));
    }

    public function test_unpack__truncate_J() {

        if (PHP_INT_SIZE == 4) {
            return;
        }

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('J1f1/J1f2', $input);
        $this->assertEquals(0, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertArrayHasKey('f2', $data);
        $this->assertCount(1, $data['f1']);
        $this->assertCount(1, $data['f2']);

        $this->assertEquals('0xAABBCCDD11223344', '0x' . strtoupper(dechex($data['f1'][1])));
        $this->assertEquals('0x55667788FFFFFFFF', '0x' . strtoupper(dechex($data['f2'][1])));

        $input = pack('NNNN', 0xAABBCCDD, 0x11223344, 0x55667788, 0xFFFFFFFF);
        $data = UtilBin::unpack('J1f1', $input);
        $this->assertEquals(8, strlen($input));
        $this->assertArrayHasKey('f1', $data);
        $this->assertCount(1, $data['f1']);
        $this->assertEquals('0xAABBCCDD11223344', '0x' . strtoupper(dechex($data['f1'][1])));
    }
}