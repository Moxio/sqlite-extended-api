<?php
namespace Moxio\SQLiteExtendedAPI;

use Moxio\SQLiteExtendedAPI\FFI\PDO\DriverDataResolver as PDODriverDataResolver;
use Moxio\SQLiteExtendedAPI\FFI\SQLite3\ConnectionWrapper as SQLite3ConnectionWrapper;

final class Facade {
    private static PDODriverDataResolver $pdo_driver_data_resolver;
    private static SQLite3ConnectionWrapper $sqlite3_connection_wrapper;

    private function __construct() {
        // This class is not meant to be instantiated
    }

    public static function wrapPDO(\PDO $pdo): WrappedConnection {
        if (isset(self::$pdo_driver_data_resolver) === false) {
            self::$pdo_driver_data_resolver = new PDODriverDataResolver();
        }
        $pdo_driver_data_void_pointer = self::$pdo_driver_data_resolver->getDriverDataPointer($pdo);

        // Following https://github.com/php/php-src/pull/3368/files#diff-eb26679695f7db289366ef6b03ee25daR729
        $pdo_sqlite_ffi = \FFI::cdef(<<<CDEF
/* Adapted from https://github.com/php/php-src/blob/cfc704ea83c56970a72756f7d4fe464885445b5e/ext/pdo_sqlite/php_pdo_sqlite_int.h#L55 */
struct pdo_sqlite_db_handle {
    /* replaced sqlite3* by void* */
    void *db;
    /* omitted rest of struct */
};
CDEF, "pdo_sqlite.so");
        $pdo_sqlite_db_handle_pointer = $pdo_sqlite_ffi->cast("struct pdo_sqlite_db_handle*", $pdo_driver_data_void_pointer);
        $sqlite3_void_pointer = $pdo_sqlite_db_handle_pointer[0]->db;

        if (isset(self::$sqlite3_connection_wrapper) === false) {
            self::$sqlite3_connection_wrapper = new SQLite3ConnectionWrapper();
        }

        return self::$sqlite3_connection_wrapper->wrapConnection($sqlite3_void_pointer);
    }
}
