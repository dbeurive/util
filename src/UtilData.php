<?php

/**
 * This file implements data's utilities.
 */

namespace dbeurive\Util;

/**
 * Class UtilData
 * These class implements data's utilities.
 * @package dbeurive\Util
 */

class UtilData
{
    /**
     * This method returns a string that represents the PHP code that, if executed, returns a given PHP data.
     * @param mixed $inData Data to return.
     * @param string|false $inOptFilePath If the parameter's value is a string, then the method assumes that it specifies a path to a file.
     *        In this case, the method will store the generated PHP code to the file which path has been specified.
     *        Otherwise, if the parameter's value is false, the the method will return a string that represents the PHP code.
     * @return null|string If the value of `$inOptFilePath` is 'false', then the method returns a string that represents the PHP code.
     *                     If the value of `$inOptFilePath` is a string, then the method assumes that the given string represents a path.
     *                     It will store the generated PHP code to the file which path has been specified.
     * @throws \Exception
     */
    static function to_callable_php_file($inData, $inOptFilePath=false) {
        $data = serialize($inData);
        $phpCode = "
<?php
return call_user_func(function() {
    \$data = <<<EOS
$data
EOS;
    return unserialize(\$data);
});";

        if (false === $inOptFilePath) {
            return $phpCode;
        }

        if (false === file_put_contents($inOptFilePath, $phpCode)) {
            throw new \Exception("An error occured while writing data into the file \"${inOptFilePath}\".");
        }
        return null;
    }
}