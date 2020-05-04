/* Adapted from https://github.com/php/php-src/blob/cfc704ea83c56970a72756f7d4fe464885445b5e/ext/pdo_sqlite/php_pdo_sqlite_int.h#L55 */
struct pdo_sqlite_db_handle {
    /* replaced sqlite3* by void* */
    void *db;
    /* omitted rest of struct */
};
