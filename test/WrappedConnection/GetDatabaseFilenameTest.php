<?php
namespace Moxio\SQLiteExtendedAPI\Test\WrappedConnection;

use Moxio\SQLiteExtendedAPI\Facade;
use PHPUnit\Framework\TestCase;

class GetDatabaseFilenameTest extends TestCase {
    public function testReturnsDatabaseFilename() {
        $temp_dir = sys_get_temp_dir();
        $temp_file = tempnam($temp_dir, 'sqlite');
        $pdo = new \PDO('sqlite:' . $temp_file);
        $wrapped_connection = Facade::wrapPDO($pdo);
        $this->assertSame($temp_file, $wrapped_connection->getDatabaseFilename());
    }

    public function testReturnsEmptyStringForInMemoryConnection() {
        $pdo = new \PDO('sqlite::memory:');
        $wrapped_connection = Facade::wrapPDO($pdo);
        $this->assertSame("", $wrapped_connection->getDatabaseFilename());
    }
}
