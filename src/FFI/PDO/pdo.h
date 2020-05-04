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
