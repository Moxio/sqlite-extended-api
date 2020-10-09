<?php
namespace Moxio\SQLiteExtendedAPI;

final class WrappedConnection {
    /* From https://github.com/sqlite/sqlite/blob/278b0517d88d4150830a4ee2c628a55da40d186d/src/sqlite.h.in#L421 */
    private const SQLITE_OK = 0;
    /* From https://github.com/sqlite/sqlite/blob/278b0517d88d4150830a4ee2c628a55da40d186d/src/sqlite.h.in#L423 */
    private const SQLITE_ERROR = 1;

    /* From https://github.com/sqlite/sqlite/blob/278b0517d88d4150830a4ee2c628a55da40d186d/src/sqlite.h.in#L2330 */
    private const SQLITE_DBCONFIG_ENABLE_LOAD_EXTENSION = 1005;

    private \FFI $sqlite3_ffi;
    private \FFI\CData $sqlite3_pointer;

    public function __construct(\FFI $sqlite3_ffi, \FFI\CData $sqlite3_pointer) {
        $this->sqlite3_ffi = $sqlite3_ffi;
        $this->sqlite3_pointer = $sqlite3_pointer;
    }

    public function getDatabaseFilename(): string {
        return $this->sqlite3_ffi->sqlite3_db_filename($this->sqlite3_pointer, "main");
    }

    public function loadExtension(string $shared_library): bool {
        $this->sqlite3_ffi->sqlite3_db_config($this->sqlite3_pointer, self::SQLITE_DBCONFIG_ENABLE_LOAD_EXTENSION, 1, null);
        $result_code = $this->sqlite3_ffi->sqlite3_load_extension($this->sqlite3_pointer, $shared_library, null, null);

        return $result_code === self::SQLITE_OK;
    }
}
