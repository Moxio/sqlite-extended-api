<?php
namespace Moxio\SQLiteExtendedAPI;

final class WrappedConnection {
    private \FFI $sqlite3_ffi;
    private \FFI\Cdata $sqlite3_pointer;

    public function __construct(\FFI $sqlite3_ffi, \FFI\Cdata $sqlite3_pointer) {
        $this->sqlite3_ffi = $sqlite3_ffi;
        $this->sqlite3_pointer = $sqlite3_pointer;
    }

    public function getDatabaseFilename(): string {
        return $this->sqlite3_ffi->sqlite3_db_filename($this->sqlite3_pointer, "main");
    }
}
