<?php
namespace Moxio\SQLiteExtendedAPI\Test\Integration;

use Moxio\SQLiteExtendedAPI\Facade;
use PHPUnit\Framework\TestCase;

class PHPSQLite3SupportTest extends TestCase {
    public function testCanWrapConnectionsMadeThroughPHPsSQLite3Extension() {
        $temp_dir = sys_get_temp_dir();
        $temp_file = tempnam($temp_dir, 'sqlite');
        $sqlite3 = new \SQLite3($temp_file);
        $wrapped_connection = Facade::wrapSQLite3($sqlite3);
        $this->assertSame($temp_file, $wrapped_connection->getDatabaseFilename());
    }
}
