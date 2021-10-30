<?php

/**
 * Mysql
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces\Driver\Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB\Interfaces\Driver\Mysql;

interface Mysql
{

  public function from(string $tableName);
  public function countFrom(string $tableName);
  public function select(string $columns);
  public function all(array $columns = []);
  public function insert(array $data = []): bool;
  public function update(array $sD = [], array $wD = []): bool;
}