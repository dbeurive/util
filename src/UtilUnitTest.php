<?php

/**
 * This file implements utility functions for unit testing.
 */

namespace dbeurive\Util;

/**
 * Class UtilUnitTest
 *
 * The class implements utility functions for unit testing.
 *
 * @package dbeurive\Util
 */

class UtilUnitTest
{
    /**
     * Execute a private or a protected static method from a given class.
     * @param string $inClassName Name of the class.
     * @param string $inMethodName Name of the (private or protected) static method to execute.
     * @return mixed The method returns whatever the call method returns.
     */
    static public function call_private_or_protected_static_method($inClassName, $inMethodName) {
        $reflection = new \ReflectionClass($inClassName);
        $method = $reflection->getMethod($inMethodName);
        $method->setAccessible(true);
        return $method->invokeArgs(null, array_slice(func_get_args(), 2));
    }

    /**
     * Execute a private or a protected non-static method from a given class, on the context of a given object.
     * @param string $inClassName Name of the class.
     * @param string $inMethodName Name of the (private or protected) method to execute.
     * @param mixed $inObject Object that defines the execution's context.
     * @return mixed The method returns whatever the call method returns.
     */
    static public function call_private_or_protected_method($inClassName, $inMethodName, $inObject) {
        $reflection = new \ReflectionClass($inClassName);
        $method = $reflection->getMethod($inMethodName);
        $method->setAccessible(true);
        return $method->invokeArgs($inObject, array_slice(func_get_args(), 3));
    }
}