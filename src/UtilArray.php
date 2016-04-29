<?php

/**
 * This file implements arrays' utilities.
 */

namespace dbeurive\Util;

/**
 * Class UtilArray
 * This class implements arrays' utilities.
 * @package dbeurive\Util
 */

class UtilArray
{
    /**
     * Test if a given array contains a given list of keys.
     * @param array $inKeys The list of keys.
     * @param array $inArray The array.
     * @return bool If the array contains the list of keys, then the method returns true.
     *         Otherwise, it returns false.
     */
    static public function array_keys_exists(array $inKeys, array $inArray) {
        foreach ($inKeys as $_key) {
            if (! array_key_exists($_key, $inArray)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Extract a specified set of keys/values - or values only - from a given associative array.
     * @param array $inKeysToKeep Set of keys to keep.
     * @param array $inArray Associative array.
     * @param bool $inOptValuesOnly This flag indicates whether the method should keep only the values or not.
     * @return array If $inOptValuesOnly=false, then the method returns an associative array that contains the expected set of keys/values.
     *               If $inOptValuesOnly=true, then the method returns an array that contains the expected set of values.
     */
    static public function array_keep_keys(array $inKeysToKeep, array $inArray, $inOptValuesOnly=false) {
        $result = array();
        foreach ($inKeysToKeep as $_key) {
            if (array_key_exists($_key, $inArray)) {
                if ($inOptValuesOnly) {
                    $result[] = $inArray[$_key];
                } else {
                    $result[$_key] = $inArray[$_key];
                }
            }
        }
        return $result;
    }

}