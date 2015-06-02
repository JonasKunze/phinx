<?php

namespace Test\Phinx\Migration;

use Phinx\Migration\Util;

class UtilTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCurrentTimestamp()
    {
        $dt = new \DateTime('now', new \DateTimeZone('UTC'));
        $expected = $dt->format(Util::DATE_FORMAT);

        $current = Util::getCurrentTimestamp();

        // Rather than using a strict equals, we use greater/lessthan checks to
        // prevent false positives when the test hits the edge of a second.
        $this->assertGreaterThanOrEqual($expected, $current);
        // We limit the assertion time to 2 seconds, which should never fail.
        $this->assertLessThanOrEqual($expected + 2, $current);
    }

    public function testMapClassNameToFileName()
    {
        $expectedResults = array(
            'CamelCase87afterSomeBooze'   => '/^camel_case87after_some_booze\.php$/',
            'CreateUserTable'             => '/^create_user_table\.php$/',
            'LimitResourceNamesTo30Chars' => '/^limit_resource_names_to30_chars\.php$/'
        );

        foreach ($expectedResults as $input => $expectedResult) {
            $this->assertRegExp($expectedResult, Util::mapClassNameToFileName($input));
        }
    }

    public function testMapFileNameToClassName()
    {
        $expectedResults = array(
            'camel_case87after_some_booze.php' => '/^CamelCase87afterSomeBooze$/'
        );

        foreach ($expectedResults as $input => $expectedResult) {
            $this->assertRegExp($expectedResult, Util::mapFileNameToClassName($input));
        }
    }

    public function testIsValidMigrationFilePath(){
        $expectedResults = array(
            '/some/path/create_user_table.php'         => true,
            '/some/Other Path/another_migration.php'   => true,
            '/some/ugly/path/das_ist_unschön.php'   => false
        );


        foreach ($expectedResults as $input => $expectedResult) {
            $this->assertEquals($expectedResult, Util::isValidMigrationFilePath($input));
        }
    }

    public function testGetVersionFromMigrationFilePath(){
        $expectedResults = array(
            'some_migration.php' => '/^SomeMigration/'
        );

        foreach ($expectedResults as $input => $expectedResult) {
            $this->assertRegExp($expectedResult, Util::getVersionFromMigrationFilePath($input));
        }
    }

    public function testIsValidMigrationClassName()
    {
        $expectedResults = array(
            'CAmelCase'         => false,
            'CreateUserTable'   => true,
            'Test'              => true,
            'test'              => false,
            'Häßlich'         => false
        );

        foreach ($expectedResults as $input => $expectedResult) {
            $this->assertEquals($expectedResult, Util::isValidMigrationClassName($input));
        }
    }
}
