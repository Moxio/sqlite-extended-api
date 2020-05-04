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

    private const EXTENSION = 'mod_spatialite.so';
    private const EXTENSION_VERIFICATION_QUERY = "SELECT ST_AsText(ST_GeomFromText('POINT(155000 463000)'))";

    public function testLoadExtensionLoadsAnSQLiteExtension() {
        $extension_dir = ini_get('sqlite3.extension_dir') ?: '/usr/lib/x86_64-linux-gnu';
        $extension_file = $extension_dir . '/' . self::EXTENSION;
        if (!file_exists($extension_file)) {
            $this->markTestSkipped(sprintf("SQLite extension file '%s' needed for test not found", self::EXTENSION));
        }

        $pdo = new \PDO('sqlite::memory:');
        $wrapped_connection = Facade::wrapPDO($pdo);
        $wrapped_connection->loadExtension(self::EXTENSION);
        $this->assertNotFalse($pdo->query(self::EXTENSION_VERIFICATION_QUERY));
    }
}
