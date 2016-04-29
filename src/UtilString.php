<?php

/**
 * This file implements strings' utilities.
 */

namespace dbeurive\Util;

/**
 * Class UtilString
 *
 * This class implements strings' utilities.
 *
 * @package dbeurive\Util
 */

class UtilString
{
    const TRIM_BEGINNING = 1;
    const TRIM_END       = 2;
    const TRIM_BOTH      = 3;

    /**
     * Depending on the value of the second parameter:
     *   * This method removes all sequences of spaces or carriage returns from the beginning of the string.
     *   * This method removes all sequences of spaces or carriage returns from the end of the string.
     *   * This method removes all sequences of spaces or carriage returns from the beginning and from the end of the string.
     * @param string $inString String to transform.
     * @param int $inWhere The value of this parameter good be: UtilString::TRIM_END, UtilString::TRIM_BEGINNING or UtilString::TRIM_BOTH.
     *   * UtilString::TRIM_END This method removes all sequences of spaces or carriage returns from the end of the string.
     *   * UtilString::TRIM_BEGINNING This method removes all sequences of spaces or carriage returns from the beginning of the string.
     *   * UtilString::TRIM_BOTH This method removes all sequences of spaces or carriage returns from the beginning and from the end of the string.
     * @return string The method returns the processed string.
     */
    static function trim($inString, $inWhere=self::TRIM_END) {
        if (self::TRIM_END == $inWhere) {
            $inString = preg_replace('/(\r?\n|\s)+$/', '', $inString);
        } elseif (self::TRIM_BEGINNING == $inWhere) {
            $inString = preg_replace('/^(\r?\n|\s)+/', '', $inString);
        } elseif (self::TRIM_BOTH == $inWhere) {
            $inString = preg_replace('/(\r?\n|\s)+$/', '', $inString);
            $inString = preg_replace('/^(\r?\n|\s)+/', '', $inString);
        }
        return $inString;
    }

    /**
     * This method takes a string that possibly spans over several lines and transforms it so it spans over one line only.
     * Optionally:
     *    * It reduces all sequences of spaces or carriage returns to one space.
     *    * It removes trailing spaces or carriage returns from the end of the string.
     * @param string $inString String to transform.
     * @param bool $inOptShrinkSpaces If the value of this parameter is true, then the function will reduce all sequences of spaces or carriage returns to one space.
     * @param bool $inOptTrimEnd If this value is true, then the function will remove trailing spaces or carriage returns from the end of the string.
     * @return string The method returns a string that spans over one line only.
     */
    static public function text_linearize($inString, $inOptShrinkSpaces=false, $inOptTrimEnd=false) {
        $inString = preg_replace('/\r?\n/', ' ', $inString);

        if ($inOptTrimEnd) {
            $inString = preg_replace('/(\r?\n|\s)+$/', '', $inString);
        }

        if ($inOptShrinkSpaces) {
            return preg_replace('/\s+/', ' ', $inString);
        }
        return $inString;
    }
}