<?php

/**
 * BasicDB_Mysql
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces\Driver\BasicDB_Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB\Interfaces\Driver\BasicDB_Mysql;

use Closure;

interface BasicDB_Mysql
{

  public function from(string $tableName);
  public function Countfrom(string $tableName);
  public function select(string $columns);
  public function union();
  public function group(Closure $fn);
  public function where($column, string $value = '', string $mark = '=', string $logical = '&&');
  public function having($column, string $value = '', string $mark = '=', string $logical = '&&');
  public function or_where($column, string $value, string $mark = '=');
  public function or_having($column, string $value, string $mark = '=');
  public function join(string $targetTable, $joinSql, string $joinType = 'inner');
  public function leftJoin(string $targetTable, $joinSql);
  public function rightJoin(string $targetTable, $joinSql);
  public function orderBy(string $columnName, string $sort = 'ASC');
  public function groupBy(string $columnName);
  public function limit($start, $limit);
  public function sql(string $sqlq);
  public function all();
  public function first();
  public function rowCount();
  public function fetchCol();
  public function generateQuery();
  public function insert(string $tableName);
  public function set($data, $value = null);
  public function lastId();
  public function lastId2($data);
  public function lastId3();
  public function update(string $tableName);
  public function delete(string $tableName);
  public function done();
  public function total();
  public function pagination($totalRecord, $paginationLimit, $pageParamName);
  public function showPagination($url, string $class = 'active');
  public function nextPage();
  public function prevPage();
  public function getSqlString();
  public function between($column, array $values = []);
  public function notBetween($column, array $values = []);
  public function findInSet($column, $value);
  public function findInSetReverse($column, $value);
  public function in($column, $value);
  public function notIn($column, $value);
  public function like($column, $value, string $inner = 'all');
  public function notLike($column, $value, string $inner = 'all');
  public function soundex($column, $value);
  public function truncate(string $tableName);
  public function truncateAll(array $dbs = []);
  public function setAutoIncrement(string $tableName, int $ai = 1);
  public function __call(string $name, array $args);
}