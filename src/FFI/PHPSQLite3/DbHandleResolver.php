<?php
namespace Moxio\SQLiteExtendedAPI\FFI\PHPSQLite3;

use ZEngine\Core;
use ZEngine\Reflection\ReflectionValue;

class DbHandleResolver {
    private \FFI $php_sqlite3_ffi;

    public function __construct() {
        if (isset(Core::$compiler) === false) {
            Core::init();
        }

        $this->php_sqlite3_ffi = \FFI::cdef(file_get_contents(__DIR__ . "/php_sqlite3_structs.h"), "sqlite3.so");
    }

    public function getSQLite3Pointer(\SQLite3 $php_sqlite3): \FFI\CData {
        $php_sqlite3_refl_value = new ReflectionValue($php_sqlite3);
        $php_sqlite3_obj_pointer = $php_sqlite3_refl_value->getRawObject();
        $offset = $php_sqlite3_obj_pointer->handlers->offset;

        // Following https://github.com/php/php-src/blob/2a76e3a4571a7e31905a569580682e68cc003abb/ext/sqlite3/php_sqlite3_structs.h#L83
        $php_sqlite3_db_object_char_pointer = $this->php_sqlite3_ffi->cast("char*", $php_sqlite3_obj_pointer) - $offset;
        $php_sqlite3_db_object_pointer = $this->php_sqlite3_ffi->cast("struct _php_sqlite3_db_object*", $php_sqlite3_db_object_char_pointer);
        return $php_sqlite3_db_object_pointer[0]->db;
    }
}
