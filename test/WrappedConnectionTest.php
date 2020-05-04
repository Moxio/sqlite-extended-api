<?php
namespace Moxio\SQLiteExtendedAPI\Test;

use Moxio\SQLiteExtendedAPI\Facade;
use PHPUnit\Framework\TestCase;

class WrappedConnectionTest extends TestCase {
    public function testGetDatabaseFilenameReturnsDatabaseFilename() {
        $temp_dir = sys_get_temp_dir();
        $temp_file = tempnam($temp_dir, 'sqlite');
        $pdo = new \PDO('sqlite:' . $temp_file);
        $wrapped_connection = Facade::wrapPDO($pdo);
        $this->assertSame($temp_file, $wrapped_connection->getDatabaseFilename());
    }

    public function testGetDatabaseFilenameReturnsEmptyStringForInMemoryConnection() {
        $pdo = new \PDO('sqlite::memory:');
        $wrapped_connection = Facade::wrapPDO($pdo);
        $this->assertSame("", $wrapped_connection->getDatabaseFilename());
    }
}
