<?php

namespace dbeurive\UtilTest;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'SetUp.php';

use dbeurive\Util\UtilClass;

interface Machine {
    public function consumeElectricity();
}

interface PlantInterface {
    public function produceOxygen();
}

interface AnimalInterface {
    public function eat();
    public function move();
}

class Dog implements AnimalInterface {
    public function eat()
    {
        // TODO: Implement eat() method.
    }

    public function move()
    {
        // TODO: Implement move() method.
    }
}

class WeirdStuff implements PlantInterface, AnimalInterface {
    public function eat()
    {
        // TODO: Implement eat() method.
    }

    public function move()
    {
        // TODO: Implement move() method.
    }

    public function produceOxygen()
    {
        // TODO: Implement produceOxygen() method.
    }
}

class UtilClassTest extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\UtilTest\SetUp;

    public function setUp() {
        $this->__setUp();
    }

    public function test_implements_interface() {
        $this->assertTrue(UtilClass::implements_interface('\dbeurive\UtilTest\Dog', '\dbeurive\UtilTest\AnimalInterface'));
        $this->assertFalse(UtilClass::implements_interface('\dbeurive\UtilTest\Dog', '\dbeurive\UtilTest\PlantInterface'));
    }

    public function test_implements_interfaces() {
        $this->assertTrue(UtilClass::implements_interfaces('\dbeurive\UtilTest\WeirdStuff', ['\dbeurive\UtilTest\AnimalInterface', '\dbeurive\UtilTest\PlantInterface']));
        $this->assertFalse(UtilClass::implements_interfaces('\dbeurive\UtilTest\WeirdStuff', ['\dbeurive\UtilTest\AnimalInterface', '\dbeurive\UtilTest\PlantInterface', '\dbeurive\UtilTest\Machine']));
    }

    public function test_get_namespace() {
        $this->assertEquals(__NAMESPACE__, UtilClass::get_namespace(__FILE__));
        $this->assertNull(UtilClass::get_namespace(__DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php'));
    }
}