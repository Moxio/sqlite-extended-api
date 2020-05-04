<?php
namespace Moxio\SQLiteExtendedAPI;

use ZEngine\Core;
use ZEngine\Reflection\ReflectionValue;

final class Facade {
    private function __construct() {
        // This class is not meant to be instantiated
    }

    public static function wrapPDO(\PDO $pdo): WrappedConnection {
        if (isset(Core::$compiler) === false) {
            Core::init();
        }

        $pdo_refl_value = new ReflectionValue($pdo);
        $pdo_obj_pointer = $pdo_refl_value->getRawObject();
        $offset = $pdo_obj_pointer->handlers->offset;

        $pdo_sqlite_ffi = \FFI::cdef(<<<CDEF
/* From https://github.com/php/php-src/blob/d1764ca33018f1f2e4a05926c879c67ad4aa8da5/ext/pdo/php_pdo_driver.h#L432 */
struct _pdo_dbh_t {
    /* replaced pdo_dbh_methods* by void* */
    const void *methods;
    void *driver_data;
    /* omitted rest of struct */
};

/* From https://github.com/php/php-src/blob/d1764ca33018f1f2e4a05926c879c67ad4aa8da5/ext/pdo/php_pdo_driver.h#L510 */
struct _pdo_dbh_object_t {
    /* had to insert struct keyword here */
    struct _pdo_dbh_t *inner;
    /* omitted `zend_object std` */
};

/* Adapted from https://github.com/php/php-src/blob/cfc704ea83c56970a72756f7d4fe464885445b5e/ext/pdo_sqlite/php_pdo_sqlite_int.h#L55 */
struct pdo_sqlite_db_handle {
    /* replaced sqlite3* by void* */
    void *db;
    /* omitted rest of struct */
};
CDEF, "pdo_sqlite.so");

        // Following https://github.com/php/php-src/blob/d1764ca33018f1f2e4a05926c879c67ad4aa8da5/ext/pdo/php_pdo_driver.h#L520
        $pdo_dbh_object_pointer = $pdo_sqlite_ffi->cast("struct _pdo_dbh_object_t*", $pdo_sqlite_ffi->cast("char*", $pdo_obj_pointer) - $offset);
        $pdo_dbh_pointer = $pdo_dbh_object_pointer[0]->inner;

        // Following https://github.com/php/php-src/pull/3368/files#diff-eb26679695f7db289366ef6b03ee25daR729
        $pdo_sqlite_db_handle_pointer = $pdo_sqlite_ffi->cast("struct pdo_sqlite_db_handle*", $pdo_dbh_pointer[0]->driver_data);

        $sqlite3_void_pointer = $pdo_sqlite_db_handle_pointer[0]->db;

        $sqlite3_ffi = \FFI::cdef(<<<CDEF
/* From https://github.com/sqlite/sqlite/blob/278b0517d88d4150830a4ee2c628a55da40d186d/src/sqlite.h.in#L249 */
typedef struct sqlite3 sqlite3;

/* From https://github.com/sqlite/sqlite/blob/278b0517d88d4150830a4ee2c628a55da40d186d/src/sqlite.h.in#L6173 */
const char *sqlite3_db_filename(sqlite3 *db, const char *zDbName);
CDEF, "sqlite3.so");

        $sqlite3_pointer = $sqlite3_ffi->cast("struct sqlite3*", $sqlite3_void_pointer);

        return new WrappedConnection($sqlite3_ffi, $sqlite3_pointer);
    }
}
