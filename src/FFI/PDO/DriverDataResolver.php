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

        $this->pdo_ffi = \FFI::cdef(<<<CDEF
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
CDEF, "pdo.so");
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
