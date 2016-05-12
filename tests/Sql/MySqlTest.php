<?php

use dbeurive\Util\UtilSql\MySql as UtilMySql;

class MySqlTest extends PHPUnit_Framework_TestCase
{

    public function testQuoteFieldName() {

        $fieldName = 'id';
        $this->assertEquals('`id`', UtilMySql::quoteFieldName($fieldName));

        $fieldName = 'user.id';
        $this->assertEquals('`user`.`id`', UtilMySql::quoteFieldName($fieldName));
    }

    public function testQuoteFieldNameFailure1() {
        $this->expectException(\Exception::class);
        $fieldName = 'user.id.toto';
        UtilMySql::quoteFieldName($fieldName);
    }

    public function testQuoteFieldNameFailure2() {
        $this->expectException(\Exception::class);
        $fieldName = 'id`';
        UtilMySql::quoteFieldName($fieldName);
    }

    public function testQuoteFieldNameFailure3() {
        $this->expectException(\Exception::class);
        $fieldName = '`id';
        UtilMySql::quoteFieldName($fieldName);
    }

    public function testQuoteFieldsNames() {

        $fieldsNames = ['id', 'login', 'password'];
        $expected    = ['`id`', '`login`', '`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames), $expected));

        $fieldsNames = ['user.id', 'login', 'password'];
        $expected    = ['`user`.`id`', '`login`', '`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames), $expected));

        $fieldsNames = ['id', 'login', 'password'];
        $expected    = ['`user`.`id`', '`user`.`login`', '`user`.`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames, 'user'), $expected));

        $fieldsNames = ['user.id', 'login', 'password'];
        $expected    = ['`user`.`id`', '`user`.`login`', '`user`.`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames, 'user'), $expected));
    }

    public function testQuoteFieldsNamesFailure1() {
        $this->expectException(\Exception::class);

        $fieldsNames = ['user.id.', 'login', 'password'];
        UtilMySql::quoteFieldsNames($fieldsNames);
    }

    public function testQuoteFieldsNamesFailure2() {
        $this->expectException(\Exception::class);

        $fieldsNames = ['user.id`', 'login', 'password'];
        UtilMySql::quoteFieldsNames($fieldsNames);
    }

    public function testQuoteFieldsNamesFailure3() {
        $this->expectException(\Exception::class);

        $fieldsNames = ['`user.id', 'login', 'password'];
        UtilMySql::quoteFieldsNames($fieldsNames);
    }
}