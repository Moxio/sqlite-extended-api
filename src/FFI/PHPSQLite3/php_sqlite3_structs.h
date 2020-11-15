/* Adapted from https://github.com/php/php-src/blob/2a76e3a4571a7e31905a569580682e68cc003abb/ext/sqlite3/php_sqlite3_structs.h#L71 */
struct _php_sqlite3_db_object {
	int initialised;
    /* replaced sqlite3* by void* */
    void *db;
    /* omitted rest of struct */
};
