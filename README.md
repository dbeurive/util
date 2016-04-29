# Description

This package implements some basic, but frequently used, utilities.

# Installation

From the command line:

    composer require dbeurive/util.

From your composer.json file:

    {
        "require": {
            "dbeurive/util": "*"
        }
    }

# API documentation
  
The detailed documentation of the API can be extracted from the code by using [PhpDocumentor](https://www.phpdoc.org/).
The file `phpdoc.xml` contains the required configuration for `PhpDocumentor`.
To generate the API documentation, just move into the root directory of this package and run `PhpDocumentor` from this location.
  
Note:
  
> Since all the PHP code is documented using PhpDoc annotations, you should be able to exploit the auto completion feature from your favourite IDE.
If you are using Eclipse, NetBeans or PhPStorm, you probably won’t need to consult the generated API documentation.

# Quick overview

Please, refer to the API (that you can generate), or to the code itself for details.

## Arrays

| Function           | Description        |
| ------------------ | ------------------ |  
| `array_keys_exists(array $inKeys, array $inArray)` | Test if a given array contains a given list of keys. |
| `array_keep_keys(array $inKeysToKeep, array $inArray, $inOptValuesOnly=false)` | Extract a specified set of keys/values - or values only - from a given associative array. |

## Classes

| Function           | Description        |
| ------------------ | ------------------ |  
| `implements_interface($inClassName, $inInterfaceName)` | Test if a given class implements a given interface. |
| `implements_interfaces($inClassName, array $inInterfaceNames)` | Tests if a given class implements a given list of interfaces. |

## Data

| Function           | Description        |
| ------------------ | ------------------ |  
| `to_callable_php_file($inData, $inOptFilePath=false)` | Return a string that represents the PHP code that, if executed, returns a given PHP data. |

## String

| Function           | Description        |
| ------------------ | ------------------ |  
| `trim($inString, $inWhere=UtilString::TRIM_END)` | Remove spaces or carriage returns from a given string. |
| `text_linearize($inString, $inOptShrinkSpaces=false, $inOptTrimEnd=false)` |  This method takes a string that possibly spans over several lines and transforms it so it spans over one line only. |

# Examples

The unit tests are good examples.

