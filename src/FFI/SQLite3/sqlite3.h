/* From https://github.com/sqlite/sqlite/blob/278b0517d88d4150830a4ee2c628a55da40d186d/src/sqlite.h.in#L249 */
typedef struct sqlite3 sqlite3;

/* From https://github.com/sqlite/sqlite/blob/278b0517d88d4150830a4ee2c628a55da40d186d/src/sqlite.h.in#L6173 */
const char *sqlite3_db_filename(sqlite3 *db, const char *zDbName);

/* From https://github.com/sqlite/sqlite/blob/278b0517d88d4150830a4ee2c628a55da40d186d/src/sqlite.h.in#L6581 */
int sqlite3_load_extension(
  sqlite3 *db,          /* Load the extension into this database connection */
  const char *zFile,    /* Name of the shared library containing extension */
  const char *zProc,    /* Entry point.  Derived from zFile if 0 */
  char **pzErrMsg       /* Put error message here if not 0 */
);
