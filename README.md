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

Examples:

```php
$data = ['A', 'B', 'C'];
UtilData::to_callable_php_file($data, '/path/to/your/file.php');
// ...
$newData = require '/path/to/your/file.php'; // $newData = ['A', 'B', 'C'];
```

## String

| Function           | Description        |
| ------------------ | ------------------ |  
| `trim($inString, $inWhere=UtilString::TRIM_END)` | Remove spaces or carriage returns from a given string. |
| `text_linearize($inString, $inOptShrinkSpaces=false, $inOptTrimEnd=false)` |  This method takes a string that possibly spans over several lines and transforms it so it spans over one line only. |

## Code

| Function           | Description        |
| ------------------ | ------------------ |  
| `require_with_args($inPath, array $inArgs)` | Loads and executes a given PHP file, just like the function `require()`, except that it allows the caller to pass parameters to the code being executed. |

Examples:

```php
$result = UtilCode::require_with_args('/path/to/your/file', ['parameter1' => 15, 'parameter2' => 20]);
```

## SQL

### MySql

| Function                                                         | Description        |
| ---------------------------------------------------------------- | ------------------ | 
| `quoteFieldName($inFieldName)`                                   | Quote a field's name. |
| `quoteFieldsNames(array $inFieldsNames, $inOptTableName=null, $inOptDatabaseName=null)` | Quote, and optionally, fully qualify, an array of fields' names. |
| `qualifyFieldName($inFieldName, $inTableName, $inBaseName=null)` | Qualify a given field's name relatively to a given table's name, and, optionally, a given database name. |

Examples:

```php
UtilMySql::quoteFieldName('id');            // => '`id`'
UtilMySql::quoteFieldName('user.id');       // => '`user`.`id`'
UtilMySql::quoteFieldName('prod.user.id');  // => '`prod`.`user`.`id`'

UtilMySql::quoteFieldsNames(['id', 'login', 'password']);                           // => ['`id`', '`login`', '`password`']
UtilMySql::quoteFieldsNames(['user.id', 'login', 'password']);                      // => ['`user`.`id`', '`login`', '`password`']
UtilMySql::quoteFieldsNames(['id', 'login', 'password'], 'user');                   // => ['`user`.`id`', '`user`.`login`', '`user`.`password`']
UtilMySql::quoteFieldsNames(['user.id', 'login', 'password'], 'user');              // => ['`user`.`id`', '`user`.`login`', '`user`.`password`']
UtilMySql::quoteFieldsNames(['db.user.id', 'login', 'password'], 'user');           // => ['`db`.`user`.`id`', '`user`.`login`', '`user`.`password`']
UtilMySql::quoteFieldsNames(['user.id', 'login', 'password'], 'user', 'prod');      // => ['`prod`.`user`.`id`', '`prod`.`user`.`login`', '`prod`.`user`.`password`']
UtilMySql::quoteFieldsNames(['prod.user.id', 'login', 'password'], 'user', 'prod'); // => ['`prod`.`user`.`id`', '`prod`.`user`.`login`', '`prod`.`user`.`password`']

UtilMySql::qualifyFieldName('id', 'user');               // => 'user.id'
UtilMySql::qualifyFieldName(''user.id','user');          // => 'user.id'
UtilMySql::qualifyFieldName('db.user.id', 'user');       // => 'db.user.id'
UtilMySql::qualifyFieldName('id', 'user', 'db');         // => 'db.user.id'
UtilMySql::qualifyFieldName('user.id', 'user', 'db');    // => 'db.user.id'
UtilMySql::qualifyFieldName('db.user.id', 'user', 'db'); // => 'db.user.id'

```

## Unit tests

| Function                                                                   | Description        |
| -------------------------------------------------------------------------- | ------------------ | 
| `call_private_or_protected_static_method($inClassName, $inMethodName)`     | Execute a private or a protected static method from a given class. |
| `call_private_or_protected_method($inClassName, $inMethodName, $inObject)` | Execute a private or a protected non-static method from a given class, within the context of a given object. |

Examples:

```php
UtilUnitTest::call_private_or_protected_static_method('ClassToTest', '__privateStatic', 10);
UtilUnitTest::call_private_or_protected_method('ClassToTest', '__privateNonStatic', $o, 10); // $o of an instance of class "ClassToTest".
```

# Examples

The unit tests are good examples.

