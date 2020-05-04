<?php
namespace Moxio\SQLiteExtendedAPI\FFI\PDO;

use ZEngine\Core;
use ZEngine\Reflection\ReflectionValue;

class DriverDataResolver {
    private \FFI $pdo_ffi;

    public function __construct() {
        if (isset(Core::$compiler) === false) {
            Core::init();
        }

        $this->pdo_ffi = \FFI::cdef(file_get_contents(__DIR__ . "/pdo.h"), "pdo.so");
    }

    public function getDriverDataPointer(\PDO $pdo): \FFI\CData {
        $pdo_refl_value = new ReflectionValue($pdo);
        $pdo_obj_pointer = $pdo_refl_value->getRawObject();
        $offset = $pdo_obj_pointer->handlers->offset;

        // Following https://github.com/php/php-src/blob/d1764ca33018f1f2e4a05926c879c67ad4aa8da5/ext/pdo/php_pdo_driver.h#L520
        $pdo_dbh_object_char_pointer = $this->pdo_ffi->cast("char*", $pdo_obj_pointer) - $offset;
        $pdo_dbh_object_pointer = $this->pdo_ffi->cast("struct _pdo_dbh_object_t*", $pdo_dbh_object_char_pointer);
        $pdo_dbh_pointer = $pdo_dbh_object_pointer[0]->inner;

        return $pdo_dbh_pointer[0]->driver_data;
    }
}
