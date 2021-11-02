<?php

/**
 * BasicDB_Mysql
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver\BasicDB_Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace Mirarus\DB\Driver\BasicDB_Mysql;

use Mirarus\DB\DB;
use Mirarus\DB\Driver;
use Mirarus\DB\_Exception;
use Mirarus\DB\Interfaces\Driver\BasicDB_Mysql\BasicDB_Mysql as IBasicDB_Mysql;
use PDO;
use PDOException;
use Closure;

class BasicDB_Mysql extends Connect implements IBasicDB_Mysql
{

	private
	$type,
	$sql,
	$unionSql,
	$tableName,
	$where,
	$having,
	$grouped,
	$group_id,
	$join,
	$orderBy,
	$groupBy,
	$limit,
	$sqlq,
	$page,
	$totalRecord,
	$paginationLimit,
	$html;
	public $queries = [];
	public $pageCount;
	public $debug = false;
	public $paginationItem = '<li class="[active]"><a href="[url]">[text]</a></li>';
	public $reference = ['NOW()'];

	/**
	 * @param string $tableName
	 */
	public function from(string $tableName)
	{
		$this->sql = 'SELECT * FROM ' . $tableName;
		$this->tableName = $tableName;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $tableName
	 */
	public function Countfrom(string $tableName)
	{
		$this->sql = 'SELECT  COUNT(*) FROM ' . $tableName;
		$this->tableName = $tableName;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $columns
	 */
	public function select(string $columns)
	{
		$this->sql = str_replace(' * ', ' ' . $columns . ' ', $this->sql);
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	public function union()
	{
		$this->type = 'union';
		$this->unionSql = $this->sql;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param Closure $fn
	 */
	public function group(Closure $fn)
	{
		static $group_id = 0;
		$this->grouped = true;
		call_user_func_array($fn, [$this]);
		$this->group_id = ++$group_id;
		$this->grouped = false;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $value
	 * @param string $mark
	 * @param string $logical
	 */
	public function where($column, string $value = '', string $mark = '=', string $logical = '&&')
	{
		$this->where[] = [
			'column' => $column,
			'value' => $value,
			'mark' => $mark,
			'logical' => $logical,
			'grouped' => $this->grouped,
			'group_id' => $this->group_id
		];
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $value
	 * @param string $mark
	 * @param string $logical
	 */
	public function having($column, string $value = '', string $mark = '=', string $logical = '&&')
	{
		$this->having[] = [
			'column' => $column,
			'value' => $value,
			'mark' => $mark,
			'logical' => $logical,
			'grouped' => $this->grouped,
			'group_id' => $this->group_id
		];
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $value
	 * @param string $mark
	 */
	public function or_where($column, string $value, string $mark = '=')
	{
		$this->where($column, $value, $mark, '||');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $value
	 * @param string $mark
	 */
	public function or_having($column, string $value, string $mark = '=')
	{
		$this->having($column, $value, $mark, '||');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $targetTable
	 * @param string $joinType
	 */
	public function join(string $targetTable, $joinSql, string $joinType = 'inner')
	{
		$this->join[] = ' ' . strtoupper($joinType) . ' JOIN ' . $targetTable . ' ON ' . sprintf($joinSql, $targetTable, $this->tableName);
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $targetTable
	 */
	public function leftJoin(string $targetTable, $joinSql)
	{
		$this->join($targetTable, $joinSql, 'left');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $targetTable
	 */
	public function rightJoin(string $targetTable, $joinSql)
	{
		$this->join($targetTable, $joinSql, 'right');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $columnName
	 * @param string $sort
	 */
	public function orderBy(string $columnName, string $sort = 'ASC')
	{
		$this->orderBy = ' ORDER BY ' . $columnName . ' ' . $sort;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $columnName
	 */
	public function groupBy(string $columnName)
	{
		$this->groupBy = ' GROUP BY ' . $columnName;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	public function limit($start, $limit)
	{
		$this->limit = ' LIMIT ' . $start . ',' . $limit;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $sqlq
	 */
	public function sql(string $sqlq)
	{
		$this->sqlq = $sqlq;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	public function all()
	{
		try {
			$query = $this->generateQuery();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->queries[$this->sql] = $result;
			DB::setTime(microtime(true), __METHOD__);
			return $result;
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	public function first()
	{
		try {
			$query = $this->generateQuery();
			$result = $query->fetch(PDO::FETCH_ASSOC);
			$this->queries[$this->sql] = $result;
			DB::setTime(microtime(true), __METHOD__);
			return $result;
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	public function rowCount()
	{
		try {
			$query = $this->generateQuery();
			$query->fetch();
			$result = $query->rowCount();
			$this->queries[$this->sql] = $result;
			DB::setTime(microtime(true), __METHOD__);
			return $result;
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	public function fetchCol()
	{
		try {
			$query = $this->generateQuery();
			$result = $query->fetchColumn();
			$this->queries[$this->sql] = $result;
			DB::setTime(microtime(true), __METHOD__);
			return $result;
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	public function generateQuery()
	{
		if ($this->join) {
			$this->sql .= implode(' ', $this->join);
			$this->join = null;
		}
		$this->get_where('where');
		if ($this->groupBy) {
			$this->sql .= $this->groupBy;
			$this->groupBy = null;
		}
		$this->get_where('having');
		if ($this->orderBy) {
			$this->sql .= $this->orderBy;
			$this->orderBy = null;
		}
		if ($this->limit) {
			$this->sql .= $this->limit;
			$this->limit = null;
		}
		if ($this->type == 'union') {
			$this->sql = $this->unionSql . ' UNION ALL ' . $this->sql;
		}
		if ($this->debug) {
			echo $this->getSqlString();
		}
		if ($this->sqlq) {
			$this->sql .= $this->sqlq;
			$this->sqlq = null;
		}
		$this->type = '';
		$result = $this->conn->query($this->sql);
		$this->queries[$this->sql] = $result;
		DB::setTime(microtime(true), __METHOD__);
		return $result;
	}

	/**
	 * @param string $conditionType
	 */
	private function get_where(string $conditionType = 'where')
	{
		if ((is_array($this->{$conditionType}) && count($this->{$conditionType}) > 0)) {
			$whereClause = ' ' . ($conditionType == 'having' ? 'HAVING' : 'WHERE') . ' ';
			$arrs = $this->{$conditionType};
			if (is_array($arrs)) {
				foreach ($arrs as $key => $item) {
					if (
						$item['grouped'] === true &&
						(
							(
								(isset($arrs[$key - 1]) && $arrs[$key - 1]['grouped'] !== true) ||
								(isset($arrs[$key - 1]) && $arrs[$key - 1]['group_id'] != $item['group_id'])
							) ||
							(
								(isset($arrs[$key - 1]) && $arrs[$key - 1]['grouped'] !== true) ||
								(!isset($arrs[$key - 1]))
							)
						)
					) {
						$whereClause .= (isset($arrs[$key - 1]) && $arrs[$key - 1]['grouped'] == true ? ' ' . $item['logical'] : null) . ' (';
					}
					switch ($item['mark']) {
						case 'LIKE':
						$where = $item['column'] . ' LIKE "' . $item['value'] . '"';
						break;
						case 'NOT LIKE':
						$where = $item['column'] . ' NOT LIKE "' . $item['value'] . '"';
						break;
						case 'BETWEEN':
						$where = $item['column'] . ' BETWEEN "' . $item['value'][0] . '" AND "' . $item['value'][1] . '"';
						break;
						case 'NOT BETWEEN':
						$where = $item['column'] . ' NOT BETWEEN "' . $item['value'][0] . '" AND "' . $item['value'][1] . '"';
						break;
						case 'FIND_IN_SET':
						$where = 'FIND_IN_SET(' . $item['column'] . ', ' . $item['value'] . ')';
						break;
						case 'FIND_IN_SET_REVERSE':
						$where = 'FIND_IN_SET(' . $item['value'] . ', ' . $item['column'] . ')';
						break;
						case 'IN':
						$where = $item['column'] . ' IN("' . (is_array($item['value']) ? implode('", "', $item['value']) : $item['value']) . '")';
						break;
						case 'NOT IN':
						$where = $item['column'] . ' NOT IN(' . (is_array($item['value']) ? implode(', ', $item['value']) : $item['value']) . ')';
						break;
						case 'SOUNDEX':
						$where = 'SOUNDEX(' . $item['column'] . ') LIKE CONCAT(\'%\', TRIM(TRAILING \'0\' FROM SOUNDEX(\'' . $item['value'] . '\')), \'%\')';
						break;
						default:
						$where = $item['column'] . ' ' . $item['mark'] . ' ' . (preg_grep('/' . trim($item['value']) . '/i', $this->reference) ? $item['value'] : '"' . $item['value'] . '"');
						break;
					}
					if ($key == 0) {
						if (
							$item['grouped'] == false &&
							isset($arrs[$key + 1]['grouped']) == true
						) {
							$whereClause .= $where . ' ' . $item['logical'];
						} else {
							$whereClause .= $where;
						}
					} else {
						$whereClause .= ' ' . $item['logical'] . ' ' . $where;
					}
					if (
						$item['grouped'] === true &&
						(
							(
								(isset($arrs[$key + 1]) && $arrs[$key + 1]['grouped'] !== true) ||
								($item['grouped'] === true && !isset($arrs[$key + 1]))
							)
							||
							(
								(isset($arrs[$key + 1]) && $arrs[$key + 1]['group_id'] != $item['group_id']) ||
								($item['grouped'] === true && !isset($arrs[$key + 1]))
							)
						)
					) {
						$whereClause .= ' )';
					}
				}
			}
			$whereClause = rtrim($whereClause, '||');
			$whereClause = rtrim($whereClause, '&&');
			$whereClause = preg_replace('/\(\s+(\|\||&&)/', '(', $whereClause);
			$whereClause = preg_replace('/(\|\||&&)\s+\)/', ')', $whereClause);
			$this->sql .= $whereClause;
			$this->unionSql .= $whereClause;
			$this->{$conditionType} = null;
			DB::setTime(microtime(true), __METHOD__);
		}
	}

	/**
	 * @param string $tableName
	 */
	public function insert(string $tableName)
	{
		$this->sql = 'INSERT INTO ' . $tableName;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	public function set($data, $value = null)
	{
		try {
			if ($value) {
				if (strstr($value, '+')) {
					$this->sql .= ' SET ' . $data . ' = ' . $data . ' ' . $value;
					$executeValue = null;
				} elseif (strstr($value, '-')) {
					$this->sql .= ' SET ' . $data . ' = ' . $data . ' ' . $value;
					$executeValue = null;
				} else {
					$this->sql .= ' SET ' . $data . ' = :' . $data . '';
					$executeValue = [
						$data => $value
					];
				}
			} else{
				$this->sql .= ' SET ' . implode(', ', array_map(function ($item) {
					return $item . ' = :' . $item;
				}, array_keys($data)));
				$executeValue = $data;
			}
			$this->get_where('where');
			$this->get_where('having');
			$result = $this->conn->prepare($this->sql)->execute($executeValue);
			$this->queries[$this->sql] = $result;
			DB::setTime(microtime(true), __METHOD__);
			if ($result) {
				if (strstr($this->sql, 'INSERT INTO ')) {
					return $this->conn->lastInsertId();
				} else {
					return $result;
				}
			} else {
				return false;
			}
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	public function lastId()
	{
		$result = $this->conn->lastInsertId();
		DB::setTime(microtime(true), __METHOD__);
		return $result;
	}

	public function lastId2($data)
	{
		$result = $this->conn->lastInsertId($data);
		DB::setTime(microtime(true), __METHOD__);
		return $result;
	}

	public function lastId3()
	{
		$result = $this->conn->query("SELECT LAST_INSERT_ID()")->fetchColumn();
		DB::setTime(microtime(true), __METHOD__);
		return $result;
	}

	/**
	 * @param string $tableName
	 */
	public function update(string $tableName)
	{
		$this->sql = 'UPDATE ' . $tableName;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $tableName
	 */
	public function delete(string $tableName)
	{
		$this->sql = 'DELETE FROM ' . $tableName;
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	public function done()
	{
		try {
			$this->get_where('where');
			$this->get_where('having');
			$result = $this->conn->exec($this->sql);
			$this->queries[$this->sql] = $result;
			DB::setTime(microtime(true), __METHOD__);
			return $result;
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	public function total()
	{
		if ($this->join) {
			$this->sql .= implode(' ', $this->join);
			$this->join = null;
		}
		$this->get_where('where');
		if ($this->groupBy) {
			$this->sql .= $this->groupBy;
			$this->groupBy = null;
		}
		$this->get_where('having');
		if ($this->orderBy) {
			$this->sql .= $this->orderBy;
			$this->orderBy = null;
		}
		if ($this->limit) {
			$this->sql .= $this->limit;
			$this->limit = null;
		}
		$result = $this->conn->query($this->sql)->fetch(PDO::FETCH_ASSOC);
		$this->queries[$this->sql] = $result;
		DB::setTime(microtime(true), __METHOD__);
		return $result['total'];
	}

	public function pagination($totalRecord, $paginationLimit, $pageParamName)
	{
		$this->paginationLimit = $paginationLimit;
		$this->page = isset($_GET[$pageParamName]) && is_numeric($_GET[$pageParamName]) ? $_GET[$pageParamName] : 1;
		$this->totalRecord = $totalRecord;
		$this->pageCount = ceil($this->totalRecord / $this->paginationLimit);
		$start = ($this->page * $this->paginationLimit) - $this->paginationLimit;
		DB::setTime(microtime(true), __METHOD__);
		return [
			'start' => $start,
			'limit' => $this->paginationLimit
		];
	}

	/**
	 * @param string $class
	 */
	public function showPagination($url, string $class = 'active')
	{
		if ($this->totalRecord > $this->paginationLimit) {
			for ($i = $this->page - 5; $i < $this->page + 5 + 1; $i++) {
				if ($i > 0 && $i <= $this->pageCount) {
					$this->html .= str_replace(
						['[active]', '[text]', '[url]'],
						[($i == $this->page ? $class : null), $i, str_replace('[page]', $i, $url)],
						$this->paginationItem
					);
				}
			}
			DB::setTime(microtime(true), __METHOD__);
			return $this->html;
		}
	}

	public function nextPage()
	{
		return ($this->page + 1 < $this->pageCount ? $this->page + 1 : $this->pageCount);
	}

	public function prevPage()
	{
		return ($this->page - 1 > 0 ? $this->page - 1 : 1);
	}

	public function getSqlString()
	{
		$this->get_where('where');
		$this->get_where('having');
		$result = $this->showError($this->sql);
		DB::setTime(microtime(true), __METHOD__);
		return $result;
	}

	/**
	 * @param array $values
	 */
	public function between($column, array $values = [])
	{
		$this->where($column, $values, 'BETWEEN');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param array $values
	 */
	public function notBetween($column, array $values = [])
	{
		$this->where($column, $values, 'NOT BETWEEN');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	public function findInSet($column, $value)
	{
		$this->where($column, $value, 'FIND_IN_SET');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	public function findInSetReverse($column, $value)
	{
		$this->where($column, $value, 'FIND_IN_SET_REVERSE');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	public function in($column, $value)
	{
		$this->where($column, $value, 'IN');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	public function notIn($column, $value)
	{
		$this->where($column, $value, 'NOT IN');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $inner
	 */
	public function like($column, $value, string $inner = 'all')
	{
		if ($inner == 'all') {
			$value = "%$value%";
		} elseif ($inner == 'left') {
			$value = "$value%";
		} elseif ($inner == 'right') {
			$value = "%$value";
		}
		$this->where($column, $value, 'LIKE');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $inner
	 */
	public function notLike($column, $value, string $inner = 'all')
	{
		if ($inner == 'all') {
			$value = "%$value%";
		} elseif ($inner == 'left') {
			$value = "$value%";
		} elseif ($inner == 'right') {
			$value = "%$value";
		}
		$this->where($column, $value, 'NOT LIKE');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	public function soundex($column, $value)
	{
		$this->where($column, $value, 'SOUNDEX');
		DB::setTime(microtime(true), __METHOD__);
		return $this;
	}

	/**
	 * @param string $tableName
	 */
	public function truncate(string $tableName)
	{
		$result = $this->conn->query('TRUNCATE TABLE ' . $tableName);
		self::$queries[] = $result;
		DB::setTime(microtime(true), __METHOD__);
		return $result;
	}

	/**
	 * @param array $dbs
	 */
	public function truncateAll(array $dbs = [])
	{
		$query = $this->from('INFORMATION_SCHEMA.TABLES')
		->select('CONCAT("TRUNCATE TABLE `", table_schema, "`.`", TABLE_NAME, "`;") as query, TABLE_NAME as tableName')
		->in('table_schema', implode(',', $dbs))
		->all();
		$this->conn->query('SET FOREIGN_KEY_CHECKS=0;')->fetch();
		foreach ($query as $row) {
			$this->conn->setAutoIncrement($row['tableName']);
			$this->conn->query($row['query'])->fetch();
		}
		$this->conn->query('SET FOREIGN_KEY_CHECKS=1;')->fetch();
		DB::setTime(microtime(true), __METHOD__);
	}

	/**
	 * @param string      $tableName
	 * @param int|integer $ai
	 */
	public function setAutoIncrement(string $tableName, int $ai = 1)
	{
		$result = $this->conn->query("ALTER TABLE `{$tableName}` AUTO_INCREMENT = {$ai}")->fetch();
		$this->queries[] = $result;
		DB::setTime(microtime(true), __METHOD__);
		return $result;
	}

	protected function showError($error)
	{
		if ($error instanceof PDOException) {
			$this->error = $error->getMessage();
		} else {
			$this->error = $error;
		}

		if (!$this->error) return;

		DB::setTime(microtime(true), __METHOD__);
		throw new _Exception(Driver::getText(), $this->error);
	}

	/**
	 * @param string $name
	 * @param array  $args
	 */
	public function __call(string $method, array $args)
	{
		$this->showError(($method . ' Method Not Found.'));
		DB::setTime(microtime(true), __METHOD__);
	}
}