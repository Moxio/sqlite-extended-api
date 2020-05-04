<?php
namespace Moxio\SQLiteExtendedAPI;

use Moxio\SQLiteExtendedAPI\FFI\PDO\DriverDataResolver as PDODriverDataResolver;
use Moxio\SQLiteExtendedAPI\FFI\PDO\SQLite\DriverDataTraverser as PDOSQLiteDriverDataTraverser;
use Moxio\SQLiteExtendedAPI\FFI\SQLite3\ConnectionWrapper as SQLite3ConnectionWrapper;

final class Facade {
    private static PDODriverDataResolver $pdo_driver_data_resolver;
    private static PDOSQLiteDriverDataTraverser $pdo_sqlite_driver_data_traverser;
    private static SQLite3ConnectionWrapper $sqlite3_connection_wrapper;

    private function __construct() {
        // This class is not meant to be instantiated
    }

    public static function wrapPDO(\PDO $pdo): WrappedConnection {
        if (isset(self::$pdo_driver_data_resolver) === false) {
            self::$pdo_driver_data_resolver = new PDODriverDataResolver();
        }
        $pdo_driver_data_void_pointer = self::$pdo_driver_data_resolver->getDriverDataPointer($pdo);

        if (isset(self::$pdo_sqlite_driver_data_traverser) === false) {
            self::$pdo_sqlite_driver_data_traverser = new PDOSQLiteDriverDataTraverser();
        }
        $sqlite3_void_pointer = self::$pdo_sqlite_driver_data_traverser->getSQLite3Pointer($pdo_driver_data_void_pointer);

        if (isset(self::$sqlite3_connection_wrapper) === false) {
            self::$sqlite3_connection_wrapper = new SQLite3ConnectionWrapper();
        }

        return self::$sqlite3_connection_wrapper->wrapConnection($sqlite3_void_pointer);
    }
}
