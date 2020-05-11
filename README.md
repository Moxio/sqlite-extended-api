moxio/sqlite-extended-api
=========================

Exposes SQLite API's that are otherwise not available in PHP. You can connect
to an SQLite database as you normally would using PHP's `PDO` extension, then
use this library to call SQLite API methods that `PDO` does not offer (e.g.
loading extensions).

**Warning**: under the hood, this library makes use of [Z-Engine](https://github.com/lisachenko/z-engine),
which proclaims itself not ready for production until version 1.0.0. Use it at
your own risk.

Requirements
------------
This library requires PHP version 7.4 or higher with the FFI extension enabled.
It only works with x64 non-thread-safe builds of PHP.

Installation
------------
Install as a dependency using composer:
```
$ composer require moxio/sqlite-extended-api
```

Usage
-----
If you have an existing `PDO` connection to an SQLite database, you can use the
`wrapPDO()` static method on the `Facade` class to obtain access to extra SQLite
API's:

```php
<?php
use Moxio\SQLiteExtendedAPI\Facade;

// Existing PDO connection
$pdo = new \PDO('sqlite::memory:');

// Wrap it using this library
$wrapped_connection = Facade::wrapPDO($pdo);

// Call extended API's on the wrapped connection object
$wrapped_connection->loadExtension('mod_spatialite.so');
```

See the next section for methods available on the wrapped connection.

Exposed API's
-------------
Below is a short overview; see [`WrappedConnection`](src/WrappedConnection.php)
for details.

### Loading SQLite extensions
Load additional SQLite extension libraries using `loadExtension($shared_library)`:
```php
$wrapped_connection->loadExtension('mod_spatialite.so');
```
This corresponds to the [`loadExtension`](https://www.php.net/manual/en/sqlite3.loadextension.php)
method in PHP's SQLite3 extension, or [`sqlite3_load_extension](https://sqlite.org/c3ref/load_extension.html)
in the SQLite C interface.

### Obtaining the database filename
To obtain the full disk path of the database connected to, use `getDatabaseFilename()`:
```php
var_dump($wrapped_connection->getDatabaseFilename());
```
For an in-memory database, this returns an empty string.

How does this work?
-------------------
In short: we use the awesome [Z-Engine](https://github.com/lisachenko/z-engine)
project by [Alexander Lisachenko](https://twitter.com/lisachenko) and PHP's
[Foreign Function Interface (FFI)](https://www.php.net/manual/en/book.ffi.php)
to resolve your PHP variable to the raw connection pointer for the SQLite C API,
then call that C API using FFI.

A more detailed blog post is coming up...

Versioning
----------
This project adheres to [Semantic Versioning](http://semver.org/).

Contributing
------------
Contributions to this project are more than welcome. If there are other SQLite
API's that you would like to be able to use in PHP, feel free to send a PR or
to file a feature request.

License
-------
This project is released under the MIT license.

Treeware
--------
This package is [Treeware](https://treeware.earth/). If you use it in production,
then we'd appreciate it if you [**buy the world a tree**](https://plant.treeware.earth/Moxio/sqlite-extended-api)
to thank us for our work. By contributing to the Treeware forest you'll be creating
employment for local families and restoring wildlife habitats.

---
Made with love, coffee and fun by the [Moxio](https://www.moxio.com) team from
Delft, The Netherlands. Interested in joining our awesome team? Check out our
[vacancies](https://werkenbij.moxio.com/) (in Dutch).
