<?php

/**
 * This file implements utilities used to manipulate code.
 */

namespace dbeurive\Util;

/**
 * Class UtilCode
 * This class implements utilities used to manipulate code.
 * @package dbeurive\Util
 */

class UtilCode
{

    /**
     * This method loads and executes a given PHP file, just like the function `require()`, except that it allows the caller to pass parameters to the code being executed.
     * Please be aware that the loaded and executed code will be executed within the current execution context.
     * @param string $inPath Path to the PHP file to load and execute.
     * @param array $inArgs This array defines the values of the variables used within the loaded code.
     *        * Variables' names are the keys of the array.
     *        * Variables' values are the values of the array.
     *        If the loaded code uses the variables `$var1` and `$var2`, then, if you need to assign values to these variables:
     *        `$inArgs = ['var1' => 10, 'var2' => 20]`
     * @return mixed The method returns whatever the loaded code returns.
     */
    static public function require_with_args($inPath, array $inArgs) {

        foreach ($inArgs as $_name => $_value) {
            ${$_name} = $_value;
        }

        return eval(self::__loadPhp($inPath));
    }

    static private function __loadPhp($inPath) {

        $code = file_get_contents($inPath);
        if (false === $code) {
            throw new \Exception("Can not load file \"${inPath}\".");
        }
        $code = preg_replace('/\s*<\?php/i', '', $code);
        $code = preg_replace('/\?>\s*$/', '', $code);

        return $code;
    }

}