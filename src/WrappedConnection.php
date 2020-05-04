<?php
namespace Moxio\SQLiteExtendedAPI;

final class WrappedConnection {
    private \FFI $sqlite3_ffi;
    private \FFI\CData $sqlite3_pointer;

    public function __construct(\FFI $sqlite3_ffi, \FFI\CData $sqlite3_pointer) {
        $this->sqlite3_ffi = $sqlite3_ffi;
        $this->sqlite3_pointer = $sqlite3_pointer;
    }

    public function getDatabaseFilename(): string {
        return $this->sqlite3_ffi->sqlite3_db_filename($this->sqlite3_pointer, "main");
    }

    public function loadExtension(string $shared_library): void {
        $this->sqlite3_ffi->sqlite3_db_config($this->sqlite3_pointer, self::SQLITE_DBCONFIG_ENABLE_LOAD_EXTENSION, 1, null);
        $this->sqlite3_ffi->sqlite3_load_extension($this->sqlite3_pointer, $shared_library, null, null);
    }
}
