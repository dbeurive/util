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

### Examples

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

### Examples

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
| `qualifyFieldsNames(array $inFieldsNames, $inTableName, $inOptDatabaseName=null)` | Qualify a given list of fields' names relatively to a given table's name, and, optionally, a given database name. |
| `developSql($inSqlTemplate, array $inSchema, $inOptAs=false, $inOptQuote=false, array $inTags=[])` | "Develop" a SQL request. The term "develop" means "replace an expression like 'user.*' into a list of fields in SELECT statements". Please see the explanation below. |

### Examples

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
UtilMySql::qualifyFieldName('user.id','user');           // => 'user.id'
UtilMySql::qualifyFieldName('db.user.id', 'user');       // => 'db.user.id'
UtilMySql::qualifyFieldName('id', 'user', 'db');         // => 'db.user.id'
UtilMySql::qualifyFieldName('user.id', 'user', 'db');    // => 'db.user.id'
UtilMySql::qualifyFieldName('db.user.id', 'user', 'db'); // => 'db.user.id'

UtilMySql::qualifyFieldsNames(['id', 'login'], 'user');                   // => ['user.id', 'user.login']
UtilMySql::qualifyFieldsNames(['user.id', 'login'], 'user');              // => ['user.id', 'user.login']
UtilMySql::qualifyFieldsNames(['id', 'login'], 'user', 'prod');           // => ['prod.user.id', 'prod.user.login']
UtilMySql::qualifyFieldsNames(['user.id', 'login'], 'user', 'prod');      // => ['prod.user.id', 'prod.user.login']
UtilMySql::qualifyFieldsNames(['prod.user.id', 'login'], 'user', 'prod'); // => ['prod.user.id', 'prod.user.login']
```

### Explanation for method `developSql()`

First, let's consider these 3 examples:

**Example 1:**: The SQL "template" below:

    SELECT user.* FROM `user`
    
Can be _developed_ into one of these expressions: 

    SELECT user.id, user.login FROM `user`
    SELECT user.id AS 'user.id', user.login AS 'user.login' FROM `user`
    SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login' FROM `user`

**Example 2:**: The SQL "template" below:

    SELECT user.*, profile.* FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id
    
Can be _developed_ into one of these expressions:
 
    SELECT user.id, user.login, profile.id, profile.age, profile.fk_user_id FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id
    SELECT user.id AS 'user.id', user.login AS 'user.login', profile.id AS 'profile.id', profile.age AS 'profile.age', profile.fk_user_id AS 'profile.fk_user_id' FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id
    SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login', `profile`.`id` AS 'profile.id', `profile`.`age` AS 'profile.age', `profile`.`fk_user_id` AS 'profile.fk_user_id' FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id

**Example 3:**: And the (silly) SQL "template" below:

    SELECT __USER__ FROM `user` WHERE user.login='user.*'

Can be _developed_ into one of these expressions: 

    SELECT user.id, user.login FROM `user` WHERE user.login='user.*'
    SELECT user.id AS 'user.id', user.login AS 'user.login' FROM `user` WHERE user.login='user.*'
    SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login' FROM `user` WHERE user.login='user.*'

**For example 1**

    $template = "SELECT user.* FROM `user`";
    $schema   = [ 'user' => ['id', 'login'], 'profile' => ['id', 'age', 'fk_user_id'] ];
    
    $result = UtilMySql::developSql($template, $schema, false, false); // => SELECT user.id, user.login FROM `user`
    $result = UtilMySql::developSql($template, $schema, true, false);  // => SELECT user.id AS 'user.id', user.login AS 'user.login' FROM `user`
    $result = UtilMySql::developSql($template, $schema, true, true);   // => SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login' FROM `user`

**For example 2**

    $template = "SELECT user.*, profile.* FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id";
    $schema = [ 'user' => ['id', 'login'], 'profile' => ['id', 'age', 'fk_user_id'] ];

    $result = UtilMySql::developSql($template, $schema, false, false); // => SELECT user.id, user.login, profile.id, profile.age, profile.fk_user_id FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id
    $result = UtilMySql::developSql($template, $schema, true, false);  // => SELECT user.id AS 'user.id', user.login AS 'user.login', profile.id AS 'profile.id', profile.age AS 'profile.age', profile.fk_user_id AS 'profile.fk_user_id' FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id
    $result = UtilMySql::developSql($template, $schema, true, true);   // => SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login', `profile`.`id` AS 'profile.id', `profile`.`age` AS 'profile.age', `profile`.`fk_user_id` AS 'profile.fk_user_id' FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id

**For example 3**

    $template = "SELECT __USER__ FROM `user` WHERE user.login='user.*'";
    $schema   = [ 'user' => ['id', 'login'], 'profile' => ['id', 'age', 'fk_user_id'] ];
    $tags     = ['__USER__' => 'user.*'];
    
    $result = UtilMySql::developSql($template, $schema, false, false, $tags); // => SELECT user.id, user.login FROM `user`
    $result = UtilMySql::developSql($template, $schema, true, false, $tags);  // => SELECT user.id AS 'user.id', user.login AS 'user.login' FROM `user`
    $result = UtilMySql::developSql($template, $schema, true, true, $tags);   // => SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login' FROM `user`
    
    

## Unit tests

| Function                                                                   | Description        |
| -------------------------------------------------------------------------- | ------------------ | 
| `call_private_or_protected_static_method($inClassName, $inMethodName)`     | Execute a private or a protected static method from a given class. |
| `call_private_or_protected_method($inClassName, $inMethodName, $inObject)` | Execute a private or a protected non-static method from a given class, within the context of a given object. |

### Examples

```php
UtilUnitTest::call_private_or_protected_static_method('ClassToTest', '__privateStatic', 10);
UtilUnitTest::call_private_or_protected_method('ClassToTest', '__privateNonStatic', $o, 10); // $o is an instance of class "ClassToTest".
```

# Examples

The [unit tests](https://github.com/dbeurive/util/tree/master/tests) are good examples.

