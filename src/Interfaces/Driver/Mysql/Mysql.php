<?php

/**
 * Mysql
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces\Driver\Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Interfaces\Driver\Mysql;

interface Mysql
{

  public function from(string $tableName);
  public function countFrom(string $tableName);
  public function select(string $columns);
  public function where(string $column, string $value = '', string $mark = '=', string $logical = '&&');
  public function insert(string $tableName);
  public function update(string $tableName);
  public function delete(string $tableName);
  public function set($data, $value = null);
  public function done();
  public function all();
  public function first();
}