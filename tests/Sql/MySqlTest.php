<?php

namespace dbeurive\UtilTest;

use dbeurive\Util\UtilSql\MySql as UtilMySql;
use dbeurive\Util\UtilUnitTest;

class MySqlTest extends \PHPUnit_Framework_TestCase
{

    private $__schema = [];

    public function setUp()
    {
        parent::setUp();
        $this->__schema = [
            'user'    => ['id', 'login'],
            'profile' => ['id', 'age', 'fk_user_id']
        ];
    }

    // -----------------------------------------------------------------------------------------------------------------
    // Test private methods
    // -----------------------------------------------------------------------------------------------------------------

    public function test__isTokenQuoted() {
        $this->assertFalse(UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__isTokenQuoted', 'id'));
        $this->assertTrue(UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__isTokenQuoted', '`id`'));
    }

    public function test__isTokenQuotedFailure1() {
        $this->expectException(\Exception::class);
        UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__isTokenQuoted', '`id');
    }

    public function test__isTokenQuotedFailure2() {
        $this->expectException(\Exception::class);
        UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__isTokenQuoted', 'id`');
    }

    public function test__quoteToken() {
        $this->assertEquals('`id`', UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__quoteToken', 'id'));
    }

    public function test__quoteTokenFailure1() {
        $this->expectException(\Exception::class);
        UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__quoteToken', 'id`');
    }

    public function test__developTag() {

        // Will find the tag, and thus, will replace.

        $sql = "SELECT __USER__ FROM `user`";

        $expected = "SELECT user.id, user.login FROM `user`";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__developTag', $sql, $this->__schema, '__USER__', 'user.*', false, false);
        $this->assertEquals($expected, $result);

        $expected = "SELECT `user`.`id`, `user`.`login` FROM `user`";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__developTag', $sql, $this->__schema, '__USER__', 'user.*', false, true);
        $this->assertEquals($expected, $result);

        $expected = "SELECT user.id AS 'user.id', user.login AS 'user.login' FROM `user`";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__developTag', $sql, $this->__schema, '__USER__', 'user.*', true, false);
        $this->assertEquals($expected, $result);

        $expected = "SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login' FROM `user`";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__developTag', $sql, $this->__schema, '__USER__', 'user.*', true, true);
        $this->assertEquals($expected, $result);

        $sql = "SELECT\n__USER__\nFROM `user`";

        $expected = "SELECT\nuser.id, user.login\nFROM `user`";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__developTag', $sql, $this->__schema, '__USER__', 'user.*', false, false);
        $this->assertEquals($expected, $result);

        $expected = "SELECT\n`user`.`id`, `user`.`login`\nFROM `user`";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__developTag', $sql, $this->__schema, '__USER__', 'user.*', false, true);
        $this->assertEquals($expected, $result);

        $expected = "SELECT\nuser.id AS 'user.id', user.login AS 'user.login'\nFROM `user`";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__developTag', $sql, $this->__schema, '__USER__', 'user.*', true, false);
        $this->assertEquals($expected, $result);

        $expected = "SELECT\n`user`.`id` AS 'user.id', `user`.`login` AS 'user.login'\nFROM `user`";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__developTag', $sql, $this->__schema, '__USER__', 'user.*', true, true);
        $this->assertEquals($expected, $result);

        // Will not find the tag, and thus, will not replace

        $sql = "SELECT __PROFILE__ FROM `user`";
        $expected = "SELECT __PROFILE__ FROM `user`";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', '__developTag', $sql, $this->__schema, '__USER__', 'user.*', true, true);
        $this->assertEquals($expected, $result);
    }

    // -----------------------------------------------------------------------------------------------------------------
    // Test public methods
    // -----------------------------------------------------------------------------------------------------------------

    // -------------------------------------------------------------------------------------------------------------
    // quoteFieldName()
    // -------------------------------------------------------------------------------------------------------------

    public function testQuoteFieldName() {

        $fieldName = 'id';
        $this->assertEquals('`id`', UtilMySql::quoteFieldName($fieldName));

        $fieldName = '`id`';
        $this->assertEquals('`id`', UtilMySql::quoteFieldName($fieldName));

        $fieldName = 'user.id';
        $this->assertEquals('`user`.`id`', UtilMySql::quoteFieldName($fieldName));

        $fieldName = 'user.`id`';
        $this->assertEquals('`user`.`id`', UtilMySql::quoteFieldName($fieldName));

        $fieldName = '`user`.id';
        $this->assertEquals('`user`.`id`', UtilMySql::quoteFieldName($fieldName));

        $fieldName = '`user`.`id`';
        $this->assertEquals('`user`.`id`', UtilMySql::quoteFieldName($fieldName));

        $fieldName = 'db.user.id';
        $this->assertEquals('`db`.`user`.`id`', UtilMySql::quoteFieldName($fieldName));

        $fieldName = '`db`.user.id';
        $this->assertEquals('`db`.`user`.`id`', UtilMySql::quoteFieldName($fieldName));

        $fieldName = '`db`.user.`id`';
        $this->assertEquals('`db`.`user`.`id`', UtilMySql::quoteFieldName($fieldName));
    }

    public function testQuoteFieldNameFailure1() {
        $this->expectException(\Exception::class);
        $fieldName = 'db.user.id.toto';
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

    // -------------------------------------------------------------------------------------------------------------
    // developSql()
    // -------------------------------------------------------------------------------------------------------------

    public function testDevelopSql() {

        $sql      = "SELECT __USER__ FROM `user`";
        $tags     = ['__USER__' => 'user.*', '__PROFILE__' => 'profile.*'];

        $expected = "SELECT user.id, user.login FROM `user`";
        $result = UtilMySql::developSql($sql, $this->__schema, false, false, $tags);
        $this->assertEquals($expected, $result);

        $expected = "SELECT user.id AS 'user.id', user.login AS 'user.login' FROM `user`";
        $result   = UtilMySql::developSql($sql, $this->__schema, true, false, $tags);
        $this->assertEquals($expected, $result);

        $expected = "SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login' FROM `user`";
        $result   = UtilMySql::developSql($sql, $this->__schema, true, true, $tags);
        $this->assertEquals($expected, $result);

        // -------

        $sql      = "SELECT user.* FROM `user`";
        $tags     = [];

        $expected = "SELECT user.id, user.login FROM `user`";
        $result = UtilMySql::developSql($sql, $this->__schema, false, false, $tags);
        $this->assertEquals($expected, $result);

        $expected = "SELECT user.id AS 'user.id', user.login AS 'user.login' FROM `user`";
        $result = UtilMySql::developSql($sql, $this->__schema, true, false, $tags);
        $this->assertEquals($expected, $result);

        $expected = "SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login' FROM `user`";
        $result = UtilMySql::developSql($sql, $this->__schema, true, true, $tags);
        $this->assertEquals($expected, $result);

        // -------

        $sql      = "SELECT __USER__, __PROFILE__ FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id";
        $tags     = ['__USER__' => 'user.*', '__PROFILE__' => 'profile.*'];

        $expected = "SELECT user.id, user.login, profile.id, profile.age, profile.fk_user_id FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', 'developSql', $sql, $this->__schema, false, false, $tags);
        $this->assertEquals($expected, $result);

        $expected = "SELECT user.id AS 'user.id', user.login AS 'user.login', profile.id AS 'profile.id', profile.age AS 'profile.age', profile.fk_user_id AS 'profile.fk_user_id' FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', 'developSql', $sql, $this->__schema, true, false, $tags);
        $this->assertEquals($expected, $result);

        $expected = "SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login', `profile`.`id` AS 'profile.id', `profile`.`age` AS 'profile.age', `profile`.`fk_user_id` AS 'profile.fk_user_id' FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', 'developSql', $sql, $this->__schema, true, true, $tags);
        $this->assertEquals($expected, $result);

        // -------

        $sql      = "SELECT user.*, profile.* FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id";
        $tags     = [];

        $expected = "SELECT user.id, user.login, profile.id, profile.age, profile.fk_user_id FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', 'developSql', $sql, $this->__schema, false, false, $tags);
        $this->assertEquals($expected, $result);

        $expected = "SELECT user.id AS 'user.id', user.login AS 'user.login', profile.id AS 'profile.id', profile.age AS 'profile.age', profile.fk_user_id AS 'profile.fk_user_id' FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', 'developSql', $sql, $this->__schema, true, false, $tags);
        $this->assertEquals($expected, $result);

        $expected = "SELECT `user`.`id` AS 'user.id', `user`.`login` AS 'user.login', `profile`.`id` AS 'profile.id', `profile`.`age` AS 'profile.age', `profile`.`fk_user_id` AS 'profile.fk_user_id' FROM `user` INNER JOIN `profile` ON user.id=profile.fk_user_id";
        $result   = UtilUnitTest::call_private_or_protected_static_method('\dbeurive\Util\UtilSql\MySql', 'developSql', $sql, $this->__schema, true, true, $tags);
        $this->assertEquals($expected, $result);
    }

    // -------------------------------------------------------------------------------------------------------------
    // quoteFieldsNames()
    // -------------------------------------------------------------------------------------------------------------

    public function testQuoteFieldsNames() {

        // No table specified.

        $fieldsNames = ['id', 'login', 'password'];
        $expected    = ['`id`', '`login`', '`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames), $expected));

        $fieldsNames = ['user.id', 'login', 'password'];
        $expected    = ['`user`.`id`', '`login`', '`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames), $expected));

        $fieldsNames = ['db.user.id', 'login', 'table.password'];
        $expected    = ['`db`.`user`.`id`', '`login`', '`table`.`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames), $expected));

        // A table is specified.

        $fieldsNames = ['id', 'login', 'password'];
        $expected    = ['`user`.`id`', '`user`.`login`', '`user`.`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames, 'user'), $expected));

        $fieldsNames = ['user.id', 'login', 'password'];
        $expected    = ['`user`.`id`', '`user`.`login`', '`user`.`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames, 'user'), $expected));

        $fieldsNames = ['db.user.id', 'login', 'user.password'];
        $expected    = ['`db`.`user`.`id`', '`user`.`login`', '`user`.`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames, 'user'), $expected));

        // A database is specified.

        $fieldsNames = ['id', 'login', 'password'];
        $expected    = ['`db`.`user`.`id`', '`db`.`user`.`login`', '`db`.`user`.`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames, 'user', 'db'), $expected));

        $fieldsNames = ['user.id', 'login', 'db.user.password'];
        $expected    = ['`db`.`user`.`id`', '`db`.`user`.`login`', '`db`.`user`.`password`'];
        $this->assertCount(0, array_diff(UtilMySql::quoteFieldsNames($fieldsNames, 'user', 'db'), $expected));
    }

    public function testQuoteFieldsNamesFailure1() {
        // Wrong syntax
        $this->expectException(\Exception::class);
        $fieldsNames = ['user.id.', 'login', 'password'];
        UtilMySql::quoteFieldsNames($fieldsNames);
    }

    public function testQuoteFieldsNamesFailure2() {
        // Wrong syntax
        $this->expectException(\Exception::class);
        $fieldsNames = ['user.id`', 'login', 'password'];
        UtilMySql::quoteFieldsNames($fieldsNames);
    }

    public function testQuoteFieldsNamesFailure3() {
        // Wrong syntax
        $this->expectException(\Exception::class);
        $fieldsNames = ['`user.id', 'login', 'password'];
        UtilMySql::quoteFieldsNames($fieldsNames);
    }

    public function testQuoteFieldsNamesFailure4() {
        // Wrong syntax
        $this->expectException(\Exception::class);
        $fieldsNames = ['toto.db.user.id', 'login', 'password'];
        UtilMySql::quoteFieldsNames($fieldsNames);
    }

    public function testQuoteFieldsNamesFailure5() {
        // Conflict between "user.id" and the given table.
        $this->expectException(\Exception::class);
        $fieldsNames = ['user.id', 'login', 'password'];
        UtilMySql::quoteFieldsNames($fieldsNames, 'profile');
    }

    public function testQuoteFieldsNamesFailure6() {
        // Conflict between "prod.profile.id" and the given database.
        $this->expectException(\Exception::class);
        $fieldsNames = ['prod.profile.id', 'login', 'password'];
        UtilMySql::quoteFieldsNames($fieldsNames, 'profile', 'test');
    }

    // -------------------------------------------------------------------------------------------------------------
    // qualifyFieldName()
    // -------------------------------------------------------------------------------------------------------------

    public function testQualifyFieldName() {
        // Without database name
        $this->assertEquals('user.id', UtilMySql::qualifyFieldName('id', 'user'));
        $this->assertEquals('user.id', UtilMySql::qualifyFieldName('user.id', 'user'));
        $this->assertEquals('db.user.id', UtilMySql::qualifyFieldName('db.user.id', 'user'));

        // With database name.
        $this->assertEquals('db.user.id', UtilMySql::qualifyFieldName('id', 'user', 'db'));
        $this->assertEquals('db.user.id', UtilMySql::qualifyFieldName('user.id', 'user', 'db'));
        $this->assertEquals('db.user.id', UtilMySql::qualifyFieldName('db.user.id', 'user', 'db'));
    }

    public function testQualifyFieldNameFailure1() {
        // Conflict between "user.id" and the given table.
        $this->expectException(\Exception::class);
        UtilMySql::qualifyFieldName('user.id', 'profile');
    }

    public function testQualifyFieldNameFailure2() {
        // Conflict between "user.id" and the given table.
        $this->expectException(\Exception::class);
        UtilMySql::qualifyFieldName('db.user.id', 'profile');
    }

    public function testQualifyFieldNameFailure3() {
        // Conflict between "test.user.id" and the given database.
        $this->expectException(\Exception::class);
        UtilMySql::qualifyFieldName('test.user.id', 'user', 'prod');
    }

    public function testQualifyFieldNameFailure4() {
        // Conflict between "test.user.id" and the given table.
        $this->expectException(\Exception::class);
        UtilMySql::qualifyFieldName('prod.user.id', 'profile', 'prod');
    }

    public function testQualifyFieldNameFailure5() {
        // Wrong syntax.
        $this->expectException(\Exception::class);
        UtilMySql::qualifyFieldName('db.prod.user.id', 'profile', 'prod');
    }

    // -------------------------------------------------------------------------------------------------------------
    // qualifyFieldsNames()
    // -------------------------------------------------------------------------------------------------------------

    public function testQualifyFieldsNames() {
        // Without database name

        $this->assertCount(0, array_diff(['user.id'], UtilMySql::qualifyFieldsNames(['id'], 'user')));
        $this->assertCount(0, array_diff(['user.id', 'user.login'], UtilMySql::qualifyFieldsNames(['id', 'login'], 'user')));
        $this->assertCount(0, array_diff(['user.id', 'user.login'], UtilMySql::qualifyFieldsNames(['user.id', 'login'], 'user')));
        $this->assertCount(0, array_diff(['db.user.id', 'user.login'], UtilMySql::qualifyFieldsNames(['db.user.id', 'login'], 'user')));

        // With database name
        $this->assertCount(0, array_diff(['prod.user.id'], UtilMySql::qualifyFieldsNames(['id'], 'user', 'prod')));
        $this->assertCount(0, array_diff(['prod.user.id', 'prod.user.login'], UtilMySql::qualifyFieldsNames(['id', 'login'], 'user', 'prod')));
        $this->assertCount(0, array_diff(['prod.user.id', 'prod.user.login'], UtilMySql::qualifyFieldsNames(['user.id', 'login'], 'user', 'prod')));
        $this->assertCount(0, array_diff(['prod.user.id', 'prod.user.login'], UtilMySql::qualifyFieldsNames(['prod.user.id', 'login'], 'user', 'prod')));
    }


}