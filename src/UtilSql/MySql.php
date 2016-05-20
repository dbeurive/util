<?php

/**
 * This file implements various utilities used to manipulate SQL for MySql
 */

namespace dbeurive\Util\UtilSql;

/**
 * Class MySql
 */

class MySql
{
    /**
     * Qualify a given field's name relatively to a given table's name, and, optionally, a given database name.
     * @param string $inFieldName Name of the field.
     * @param string $inTableName Name of the table.
     * @param null|string $inOptDatabaseName Name of the database.
     * @return string The method returns the qualified name of the field.
     * @throws \Exception
     */
    static public function qualifyFieldName($inFieldName, $inTableName, $inOptDatabaseName=null) {

        $tokens = explode('.', $inFieldName);

        // Field's name in the form "<field name>".
        if (1 == count($tokens)) {
            array_unshift($tokens, $inTableName);
            if (! is_null($inOptDatabaseName)) {
                array_unshift($tokens, $inOptDatabaseName);
            }
            return implode('.', $tokens);
        }

        // Field's name in the form "<table name>.<field name>".
        if (2 == count($tokens)) {
            if ($tokens[0] != $inTableName) {
                throw new \Exception("Invalid couple (field's name, table name): (\"${inFieldName}\", \"${inTableName}\").");
            }
            if (! is_null($inOptDatabaseName)) {
                array_unshift($tokens, $inOptDatabaseName);
            }
            return implode('.', $tokens);
        }

        if (3 == count($tokens)) {
            if ($tokens[1] != $inTableName) {
                throw new \Exception("Invalid couple (field's name, table name): (\"${inFieldName}\", \"${inTableName}\").");
            }

            if (! is_null($inOptDatabaseName)) {
                if ($tokens[0] != $inOptDatabaseName) {
                    throw new \Exception("Invalid tuple (field's name, table name, database name): (\"${inFieldName}\", \"${inTableName}\", \"${inOptDatabaseName}\").");
                }
            }
            return implode('.', $tokens);
        }

        throw new \Exception("Invalid field's name \"${inFieldName}\".");
    }

    /**
     * Qualify a given list of fields' names relatively to a given table's name, and, optionally, a given database name.
     * @param array $inFieldsNames List of fields' names.
     * @param string $inTableName Name of the table.
     * @param null|string $inOptDatabaseName Name of the database.
     * @return mixed
     * @throws \Exception
     */
    static public function qualifyFieldsNames(array $inFieldsNames, $inTableName, $inOptDatabaseName=null) {

        return array_map(function($e) use($inTableName, $inOptDatabaseName) {
            return self::qualifyFieldName($e, $inTableName, $inOptDatabaseName);
        }, $inFieldsNames);
    }
    
    /**
     * Quote a field's name.
     * @param string $inFieldName Name of the field to quote.
     *        The name can be fully qualified ("<table name>.<field name>") or not ("<field name>").
     * @return string The method returns the quoted field.
     *         If the given field's name was fully qualified ("<table name>.<field name>"), then the format of the returned value is "`<table name>`.`<field name>`".
     *         Otherwise, the format of the returned value is `<field name>`.
     * @throws \Exception
     */
    static public function quoteFieldName($inFieldName) {

        $tokens = explode('.', $inFieldName);

        if (1 <= count($tokens) && count($tokens) <= 3) {
            return implode('.', array_map(function ($e) { return self::__quoteToken($e); }, $tokens));
        }

        throw new \Exception("Invalid field's name \"${inFieldName}\".");
    }

    /**
     * Quote an array of fields' names.
     *   * quoteFieldsNames(['id', 'login', 'password'])              => ['`id`', '`login`', '`password`']
     *   * quoteFieldsNames(['user.id', 'login', 'password'])         => ['`user`.`id`', '`login`', '`password`']
     *   * quoteFieldsNames(['id', 'login', 'password'], 'user')      => ['`user`.`id`', '`user`.`login`', '`user`.`password`']
     *   * quoteFieldsNames(['user.id', 'login', 'password'], 'user') => ['`user`.`id`', '`user`.`login`', '`user`.`password`']
     * @param array $inFieldsNames Array of fields' names.
     *        The names can be fully qualified ("<table name>.<field name>") or not ("<field name>").
     * @param null|string $inOptTableName Name of a table used to fully qualify fields that need to.
     * @param null|string $inOptDatabaseName Name of a database used to fully qualify fields that need to.
     * @return array The method returns an array of quoted fields' names.
     * @throws \Exception
     */
    static public function quoteFieldsNames(array $inFieldsNames, $inOptTableName=null, $inOptDatabaseName=null) {

        $qualifier = function($e) {
            return $e;
        };

        if (is_null($inOptTableName) && !is_null($inOptDatabaseName)) {
            throw new \Exception("Invalid use of the method \"quoteFieldsNames()\"!");
        }

        if (! is_null($inOptTableName) && is_null($inOptDatabaseName)) {
            $qualifier = function($e) use ($inOptTableName, $inOptDatabaseName) {

                $tokens = explode('.', $e);

                if (count($tokens) == 1) {
                    return "${inOptTableName}.${e}";
                }

                if (count($tokens) == 2) {
                    if ($tokens[0] != $inOptTableName) {
                        throw new \Exception("Invalid field's name \"${e}\". The name of the table conflicts with the specified one \"${inOptTableName}\".");
                    }
                    return "${e}";
                }

                if (count($tokens) == 3) {
                    if ($tokens[1] != $inOptTableName) {
                        throw new \Exception("Invalid field's name \"${e}\". The name of the table conflicts with the specified one \"${inOptTableName}\".");
                    }
                    return "${e}";
                }

                throw new \Exception("Invalid field's name \"${e}\".");
            };
        }

        if (!is_null($inOptTableName) && !is_null($inOptDatabaseName)) {

            $qualifier = function($e) use ($inOptTableName, $inOptDatabaseName) {

                $tokens = explode('.', $e);

                if (count($tokens) == 1) {
                    return "${inOptDatabaseName}.${inOptTableName}.${e}";
                }

                if (count($tokens) == 2) {
                    if ($tokens[0] != $inOptTableName) {
                        throw new \Exception("Invalid field's name \"${e}\". The name of the table conflicts with the specified one \"${inOptTableName}\".");
                    }
                    return "${inOptDatabaseName}.${e}";
                }

                if (count($tokens) == 3) {
                    if ($tokens[0] != $inOptDatabaseName) {
                        throw new \Exception("Invalid field's name \"${e}\". The name of the database conflicts with the specified one \"${inOptDatabaseName}\".");
                    }
                    if ($tokens[1] != $inOptTableName) {
                        throw new \Exception("Invalid field's name \"${e}\". The name of the table conflicts with the specified one \"${inOptTableName}\".");
                    }
                    return "${e}";
                }

                throw new \Exception("Invalid field's name \"${e}\".");
            };
        }

        $f = function($e) use ($qualifier) { return self::quoteFieldName($qualifier($e)); };
        return array_map($f, $inFieldsNames);
    }

    /**
     * "Develop" a SQL request. The term "develop" means "replace an expression like 'user.*' into a list of fields in SELECT statements".
     * Selected fields' names may be "quoted" or "explicitly named using AS", or both.
     *    Quoted only:                       "`user`.`id`, `user`.`login`..."
     *    Not Quoted only:                   "user.id, user.login..."
     *    Quoted and explicitly named:       "`user`.`id` as 'user.id', `user`.`login` as 'user.login'..."
     *    Not quoted and explicitly named:   "user.id as 'user.id', user.login as 'user.login'..."
     * @param string $inSqlTemplate The SQL template.
     *        Examples: "SELECT user.* FROM `user`"
     *                  "SELECT __USER__ FROM `user`" (this implies that you specify tags - see the last parameters $inTags)
     * @param array $inSchema Database' schema.
     *        This parameter is an array which keys are the tables' names and the values the list of fields.
     * @param bool $inOptAs Should the selected fields be explicitly named using "AS" ?
     * @param bool $inOptQuote This flag indicates whether the fields' names within the SQL fragment should be quoted or not.
     * @param array $inTags When you can not specify the fields' names to develop using the notation "<table name>.*" (for example: "user.*"), then you can use this parameter.
     *        This situation may arise if, within the SQL request, you have a value equal to "user.*", for example.
     *        For example: SELECT user.* FROM user WHERE user.login='user.*'
     *        OK, this is a silly example, by it illustrates the situation.
     *        In this case, you put unique tags in your SQL request, and you specify how the method should handle these tags.
     *        For example: SELECT __USER__, __PROFILE__ FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id
     *                     $inTags = ['__USER__' => 'user.*', '__PROFILE__' => 'profile.*']
     * @return string
     * @throws \Exception
     */
    static public function developSql($inSqlTemplate, array $inSchema, $inOptAs=false, $inOptQuote=false, array $inTags=[]) {

        if (0 == count($inTags)) {
            /**
             * @var string $_tableName
             * @var array $_fieldsNames
             */
            $newTags = [];
            foreach ($inSchema as $_tableName => $_fieldsNames) {
                $newTags["${_tableName}.\\*"] = "${_tableName}.*";
            }
            $inTags = $newTags;
        }

        /**
         * @var string $_tag
         * @var string $_replacement
         */
        foreach ($inTags as $_tag => $_replacement) {
            $inSqlTemplate = self::__developTag($inSqlTemplate, $inSchema, $_tag, $_replacement, $inOptAs, $inOptQuote);
        }

        return $inSqlTemplate;
    }

    /**
     * Replace a tag by a "select SQL fragment" within a SQL template.
     * Selected fields' names may be "quoted" or "explicitly named using AS", or both.
     *    Quoted only:                       "`user`.`id`, `user`.`login`..."
     *    Not Quoted only:                   "user.id, user.login..."
     *    Quoted and explicitly named:       "`user`.`id` as 'user.id', `user`.`login` as 'user.login'..."
     *    Not quoted and explicitly named:   "user.id as 'user.id', user.login as 'user.login'..."
     * @param string $inSqlTemplate SQL template.
     * @param array $inSchema Database' schema.
     *        This parameter is an array which keys are the tables' names and the values the list of fields.
     * @param string $inTag Tag to search for.
     * @param string $inReplacementSpec Replacement' specification (ex: "user.*" or "`user`.*").
     * @param bool $inOptAs Should the selected fields be explicitly named using "AS" ? 
     * @param bool $inOptQuote This flag indicates whether the fields' names within the SQL fragment should be quoted or not.
     * @return string
     * @throws \Exception
     */
    static private function __developTag($inSqlTemplate, array $inSchema, $inTag, $inReplacementSpec, $inOptAs=false, $inOptQuote=false) {

        // Make sure that the replacement is valid.
        // Valid string is: "<table name>.*" (ex: "user.*").
        $specTokens = [];
        if (0 === preg_match('/^([\w]+)\.\*$/', $inReplacementSpec, $specTokens)) {
            throw new \Exception("Invalid replacement specification \"${inReplacementSpec}\".");
        }
        $tableName = $specTokens[1];

        // Is the name of the table quoted ? (ex: `user`.*)
        // The line below may throw an exception!
        if (self::__isTokenQuoted($tableName)) {
            $tableName = substr($tableName, 1, -1);
        }

        // Make sure that the specified table exists.
        if (! array_key_exists($tableName, $inSchema)) {
            throw new \Exception("Invalid replacement specification \"${inReplacementSpec}\". The table \"${tableName}\" does not exist.");
        }

        // Now perform the replacement.
        $tokens = preg_split("/${inTag}/", $inSqlTemplate);
        if (0 == count($tokens)) return $inSqlTemplate;

        $glue = ''; // Unlike many languages, this is not necessary... but I write it anyway.
        $qualified = self::qualifyFieldsNames($inSchema[$tableName], $tableName); // ['user.id', 'user.login'...]
        $quoter = $inOptQuote ? function($e) { return self::quoteFieldName($e); } : function($e) { return $e; };

        if ($inOptAs) {
            $glue = implode(', ', array_map(function($e) use($quoter) { return "{$quoter($e)} AS '${e}'"; }, $qualified));
        } else {
            $glue = implode(', ', array_map(function($e) use($quoter) { return "{$quoter($e)}"; }, $qualified));
        }

        return implode($glue, $tokens);
    }

    /**
     * Test if a "word" is "quoted".
     * Example: "`user`" is quoted, but "user" is not.
     * @param string $inToken word to test.
     * @return bool
     * @throws \Exception
     */
    static private function __isTokenQuoted($inToken) {

        if (preg_match('/^`[^`]+`$/', $inToken)) {
            return true;
        }

        if (preg_match('/^[^`]+$/', $inToken)) {
            return false;
        }

        throw new \Exception("Invalid token: \"${inToken}\".");
    }

    /**
     * Quote a "word".
     * Example: "user" is quoted as "`user`".
     * @param string $inToken Word to quote.
     * @return string The method returns the quoted word.
     * @throws \Exception
     */
    static private function __quoteToken($inToken) {

        if (self::__isTokenQuoted($inToken)) {
            return $inToken;
        }

        return '`' . $inToken . '`';
    }
}