<?php
namespace Moxio\SQLiteExtendedAPI\FFI\PDO\SQLite;

class DriverDataTraverser {
    private \FFI $pdo_sqlite_ffi;

    public function __construct() {
        $this->pdo_sqlite_ffi = \FFI::cdef(file_get_contents(__DIR__ . "/pdo_sqlite.h"), "pdo_sqlite.so");
    }

    public function getSQLite3Pointer(\FFI\CData $driver_data_void_pointer): \FFI\CData {
        $pdo_sqlite_db_handle_pointer = $this->pdo_sqlite_ffi->cast("struct pdo_sqlite_db_handle*", $driver_data_void_pointer);
        return $pdo_sqlite_db_handle_pointer[0]->db;
    }
}
