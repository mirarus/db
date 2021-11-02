# Mirarus PHP Database Lib

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

require 'vendor/autoload.php';

use Mirarus\DB\DB;
use Mirarus\DB\Connect;

/**
 * Driver Usage List
 *
 * --/Mysql
 * 
 *  Set Driver: mysql
 *  Set DSN: 'mysql:host=localhost;dbname=testdb;charset=utf8', 'root', 'mysql'
 *  
 *   [localhost, testdb, root, mysql] Changed
 *
 * 
 * --/MongoDB
 *  
 *  Set Driver: mongodb
 *  Set DSN: 'mongodb://localhost', 'test'
 *  
 *   [localhost, test] Changed
 *
 *
 * /****\
 *
 *  Global Values
 *  	_DB
 *  	_DB__$driverName
 *  	
 */

/**
 * Connection Static Usage
 */
Connect::dsn('mysql:host=localhost;dbname=testdb;charset=utf8', 'root', 'mysql');
Connect::driver('mysql');

$db = new DB(new Connect);

/**
 * Connection Return Usage
 */
$connect = new Connect();
$connect->driver('mysql');
$connect->dsn('mysql:host=localhost;dbname=testdb;charset=utf8', 'root', 'mysql');

$connect = new Connect('mysql', 'mysql:host=localhost;dbname=testdb;charset=utf8', 'root', 'mysql');

$db = new DB($connect);

/*
$result = $db
->insert('users')
->set([
  'name' => 'Ali', 
  'surname' => 'Güçlü'
]);

$result = $db
->from('users')
->update([
	'name' => 'Ali X', 
	'surname' => 'Güçlü X'
], ['_id' => 14]);
*/

/*$result = $db
->update('users')
->where('_id', 14)
->set([
  'name' => 'Ali', 
  'surname' => 'Güçlüxxddds'
]);
*/

$result = $db->from('users')->all();


var_dump($result);

var_dump($db);

?>
```

## License

Licensed under the MIT license, see [LICENSE](LICENSE)
