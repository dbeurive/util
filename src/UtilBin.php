<?php

/**
 * This file implements binary tools.
 */

namespace dbeurive\Util;

/**
 * Class UtilBin
 *
 * This class implements binary tools.
 *
 * @package dbeurive\Util
 */

class UtilBin
{
    /**
     * Like the PHP unpack function, this function unpacks data from a binary string.
     * This function is a wrapper around the original unpack function. However it
     * behaves differently. Differences are listed below:
     * * It uses the same format specification that the PHP unpack function. However,
     *   all formats are not supported. Supported formats are: c, C, s, S, n, v l, L, N,
     *   V, q, Q, J, P.
     * * The result is organised differently.
     * * By default, the given binary string is truncated once binary data has been
     *   extracted.
     * @param string $inFormat See the description for the PHP unpack function.
     * @param string $inOutData Binary string that contains the data to extract.
     * @param bool $inOptTruncate This flag indicates whether the function should
     *             truncate the given binary string or not. If the given value is true,
     *             then the binary string will be truncated.
     * @return array The function returns an associative array that contains the extracted data.
     * @see http://php.net/manual/fr/function.pack.php
     * @see http://php.net/manual/fr/function.unpack.php
     */

    static public function unpack($inFormat, &$inOutData, $inOptTruncate=true) {

        $sizes = array(
            'c' => function($n) { return $n; },   // signed char
            'C' => function($n) { return $n; },   // unsigned char
            's' => function($n) { return 2*$n; }, // signed short (always 16 bit, machine byte order)
            'S' => function($n) { return 2*$n; }, // unsigned short (always 16 bit, machine byte order)
            'n' => function($n) { return 2*$n; }, // unsigned short (always 16 bit, big endian byte order)
            'v' => function($n) { return 2*$n; }, // unsigned short (always 16 bit, little endian byte order)
            'l' => function($n) { return 4*$n; }, // signed long (always 32 bit, machine byte order)
            'L' => function($n) { return 4*$n; }, // unsigned long (always 32 bit, machine byte order)
            'N' => function($n) { return 4*$n; }, // unsigned long (always 32 bit, big endian byte order)
            'V' => function($n) { return 4*$n; }, // unsigned long (always 32 bit, little endian byte order)
            'q' => function($n) { return 8*$n; }, // signed long long (always 64 bit, machine byte order)
            'Q' => function($n) { return 8*$n; }, // unsigned long long (always 64 bit, machine byte order)
            'J' => function($n) { return 8*$n; }, // unsigned long long (always 64 bit, big endian byte order)
            'P' => function($n) { return 8*$n; }  // unsigned long long (always 64 bit, little endian byte order)
        );

        // Check the specification and rename the expected matches.
        $specifications = explode('/', $inFormat);
        $name2size = array();

        /**
         * @var int $_index
         * @var string $_specification
         */
        foreach ($specifications as $_index => $_specification) {
            $matches = array();
            if (1 !== preg_match('/^([a-z])(\d+|\*)([a-z_\-\d]+)/i', $_specification, $matches)) {
                return false;
            }

            $type  = $matches[1];
            $count = $matches[2];
            $name  = $matches[3] . '.';

            if (array_key_exists($name, $name2size)) { return false; }
            $name2size[$name] = $sizes[$type];
            $specifications[$_index] = "${type}${count}${name}";
        }
        $inFormat = implode('/', $specifications);
        $result = array();
        $data = unpack($inFormat, $inOutData);

        /**
         * @var string $_key
         * @var mixed $_value
         */
        foreach ($data as $_key => $_value) {

            $matches = array();
            if (1 !== preg_match('/^([a-z_\-\d]+)\.(\d+)?$/i', $_key, $matches)) {
                print "Invalid: $_key\n";
                return false;
            }
            // Warning: keep in mind that the first element of the array $matches contains the full string that matches
            // the regular expression. Therefore, the array has, at least, 2 elements.
            $name = $matches[1];
            $position = count($matches) > 2 ? $matches[2] : '1';
            if (! array_key_exists($name, $result)) {
                $result[$name] = array();
            }
            $result[$name][$position] = $_value;
        }

        // Truncate the data, if required.
        if ($inOptTruncate) {
            $length = 0;
            /**
             * @var string $_name
             * @var array $_values
             */
            foreach ($result as $_name => $_values) {
                $length += call_user_func($name2size["${_name}."], count($_values));
            }
            $inOutData = strlen($inOutData) == $length ? '' : substr($inOutData, $length);
        }

        return $result;
    }
}