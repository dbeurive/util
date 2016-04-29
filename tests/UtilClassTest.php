<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'setUp.php';

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

class UtilClassTest extends PHPUnit_Framework_TestCase
{
    use SetUp;

    public function setUp() {
        $this->__setUp();
    }

    public function test_implements_interface() {
        $this->assertTrue(UtilClass::implements_interface('Dog', 'AnimalInterface'));
        $this->assertFalse(UtilClass::implements_interface('Dog', 'PlantInterface'));
    }

    public function test_implements_interfaces() {
        $this->assertTrue(UtilClass::implements_interfaces('WeirdStuff', ['AnimalInterface', 'PlantInterface']));
        $this->assertFalse(UtilClass::implements_interfaces('WeirdStuff', ['AnimalInterface', 'PlantInterface', 'Machine']));
    }
}