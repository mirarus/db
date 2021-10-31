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

/**
 * Connection Mini Usage
 */

$db = new DB('mysql', 'mysql:host=localhost;dbname=testdb;charset=utf8', 'root', 'mysql');


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

$result = $db->from('users')->all();


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