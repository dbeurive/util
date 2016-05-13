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





    static private function __isTokenQuoted($inToken) {

        if (preg_match('/^`[^`]+`$/', $inToken)) {
            return true;
        }

        if (preg_match('/^[^`]+$/', $inToken)) {
            return false;
        }

        throw new \Exception("Invalid token: \"${inToken}\".");
    }


    static private function __quoteToken($inToken) {

        if (self::__isTokenQuoted($inToken)) {
            return $inToken;
        }

        return '`' . $inToken . '`';
    }
}