<?php

/**
 * This file implements class' utilities.
 */

namespace dbeurive\Util;

/**
 * Class UtilClass
 * This class implements class' utilities.
 * @package dbeurive\Util
 */

class UtilClass
{
    /**
     * Tests if a given class implements a given interface.
     * @param string $inClassName Class' name.
     * @param string $inInterfaceName Interface's name.
     * @return bool If the class implements the interface, then the method returns the value true.
     *         Otherwise the method returns the value false.
     */
    public static function implements_interface($inClassName, $inInterfaceName) {
        $implementations = class_implements($inClassName);

        foreach ($implementations as $_interfaceName => $_value) {
            if ($_interfaceName == $inInterfaceName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Tests if a given class implements a given list of interfaces.
     * @param string $inClassName Class' name.
     * @param string $inInterfaceNames List of interfaces.
     * @return bool If the class implements the given list of interfaces, then the method returns the value true.
     *         Otherwise the method returns the value false.
     */
    public static function implements_interfaces($inClassName, array $inInterfaceNames) {
        foreach ($inInterfaceNames as $_name) {
            if (! self::implements_interface($inClassName, $_name)) {
                return false;
            }
        }
        return true;
    }
}