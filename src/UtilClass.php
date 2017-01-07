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
        $inInterfaceName = preg_replace('/^\\\/', '', $inInterfaceName);
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

        $inInterfaceNames = array_map(function($e) { return preg_replace('/^\\\/', '', $e); }, $inInterfaceNames);

        foreach ($inInterfaceNames as $_name) {
            if (! self::implements_interface($inClassName, $_name)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Extract the namespace from a given PHP file.
     * @param string $inPath Path to the PHP file.
     * @return null|string If a namespace is found, then the method returns a string that represents the namespace.
     *         Otherwise, it returns the value null.
     * @throws \Exception
     */
     static public function get_namespace($inPath) {

        if (false === $php = file_get_contents($inPath)) {
            throw new \Exception("Could not load the content of the file \"$inPath\".");
        }
        $tokens = token_get_all($php);
        $count = count($tokens);
        $i = 0;
        $namespace = '';
        $namespaceFound = false;
        while ($i < $count) {
            $token = $tokens[$i];
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                // Found namespace declaration
                while (++$i < $count) {
                    if ($tokens[$i] === ';') {
                        $namespaceFound = true;
                        $namespace = trim($namespace);
                        break;
                    }
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }
                break;
            }
            $i++;
        }
        if (!$namespaceFound) {
            return null;
        } else {
            return $namespace;
        }
    }
}