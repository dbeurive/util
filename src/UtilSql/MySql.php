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
     * Quote a field's name.
     * @param string $inFieldName Name of the field to quote.
     *        The name can be fully qualified ("<table name>.<field name>") or not ("<field name>").
     * @return string The method returns the quoted field.
     *         If the given field's name was fully qualified ("<table name>.<field name>"), then the format of the returned value is "`<table name>`.`<field name>`".
     *         Otherwise, the format of the returned value is `<field name>`.
     * @throws \Exception
     */
    static public function quoteFieldName($inFieldName) {

        if (false !== strpos($inFieldName, '`')) {
            throw new \Exception("Invalid field's name ${inFieldName}.");
        }

        $tokens = explode('.', $inFieldName);
        
        if (1 == count($tokens)) {
            return '`' . $tokens[0] . '`';
        }

        if (2 == count($tokens)) {
            return '`' . $tokens[0] . '`' . '.' . '`' . $tokens[1] . '`';
        }

        throw new \Exception("Invalid field's name ${inFieldName}.");
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
     * @return array The method returns an array of quoted fields' names.
     * @throws \Exception
     */
    static public function quoteFieldsNames(array $inFieldsNames, $inOptTableName=null) {

        $qualifier = function($e) use ($inOptTableName) {
            if (0 === strpos($e, "${inOptTableName}.")) { return $e; }
            return "${inOptTableName}.${e}";
        };

        $qualifier = empty($inOptTableName) ? function($e) { return $e; } : $qualifier;
        $f = function($e) use ($qualifier) { return self::quoteFieldName($qualifier($e)); };
        return array_map($f, $inFieldsNames);
    }
}