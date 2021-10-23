# Mirarus Database

Mirarus Database Library

[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/mirarus/db?style=flat-square&logo=php)](https://packagist.org/packages/mirarus/db)
[![Packagist Version](https://img.shields.io/packagist/v/mirarus/db?style=flat-square&logo=packagist)](https://packagist.org/packages/mirarus/db)
[![Packagist Downloads](https://img.shields.io/packagist/dt/mirarus/db?style=flat-square&logo=packagist)](https://packagist.org/packages/mirarus/db)
[![Packagist License](https://img.shields.io/packagist/l/mirarus/db?style=flat-square&logo=packagist)](https://packagist.org/packages/mirarus/db)
[![PHP Composer](https://img.shields.io/github/workflow/status/mirarus/db/PHP%20Composer/main?style=flat-square&logo=php)](https://github.com/mirarus/db/actions/workflows/php.yml)


## Installation

Install using composer:

```bash
composer require mirarus/db
```

## Example

Install using composer:

```bash
<?php

  require_once __DIR__ . '/vendor/autoload.php';

  use Mirarus\DB\DB;

  /*
  DB::dsn('mysql:host=localhost;dbname=testdb;charset=utf8', 'root', 'mysql');
  DB::dsn('mongodb://127.0.0.1', 'test');
  */

  DB::dsn('mysql:host=localhost;dbname=testdb;charset=utf8', 'root', 'mysql');
  DB::connect('mysql');

  var_dump($_DB);
  var_dump($_DB__Mysql);
  var_dump($_DB__MongoDB);

  $result = $_DB
  ->insert('users', [
    'name' => 'Ali', 
    'surname' => 'Güçlü'
  ]);

  var_dump($result);
?>
```

## License

Licensed under the MIT license, see [LICENSE](LICENSE)
