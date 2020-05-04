<?php
namespace Moxio\SQLiteExtendedAPI\FFI\PDO\SQLite;

class DriverDataTraverser {
    private \FFI $pdo_sqlite_ffi;

    public function __construct() {
        $this->pdo_sqlite_ffi = \FFI::cdef(<<<CDEF
/* Adapted from https://github.com/php/php-src/blob/cfc704ea83c56970a72756f7d4fe464885445b5e/ext/pdo_sqlite/php_pdo_sqlite_int.h#L55 */
struct pdo_sqlite_db_handle {
    /* replaced sqlite3* by void* */
    void *db;
    /* omitted rest of struct */
};
CDEF, "pdo_sqlite.so");
    }

    public function getSQLite3Pointer(\FFI\CData $driver_data_void_pointer): \FFI\CData {
        $pdo_sqlite_db_handle_pointer = $this->pdo_sqlite_ffi->cast("struct pdo_sqlite_db_handle*", $driver_data_void_pointer);
        return $pdo_sqlite_db_handle_pointer[0]->db;
    }
}
