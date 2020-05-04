<?php
namespace Moxio\SQLiteExtendedAPI\FFI\SQLite3;

use Moxio\SQLiteExtendedAPI\WrappedConnection;

class ConnectionWrapper {
    private \FFI $sqlite3_ffi;

    public function __construct() {
        $this->sqlite3_ffi = \FFI::cdef(file_get_contents(__DIR__ . "/sqlite3.h"), "sqlite3.so");
    }

    public function wrapConnection(\FFI\CData $sqlite3_void_pointer): WrappedConnection {
        $sqlite3_pointer = $this->sqlite3_ffi->cast("struct sqlite3*", $sqlite3_void_pointer);
        return new WrappedConnection($this->sqlite3_ffi, $sqlite3_pointer);
    }
}
