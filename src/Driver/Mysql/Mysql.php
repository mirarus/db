<?php

/**
 * Mysql
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver\Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace Mirarus\DB\Driver\Mysql;

use Mirarus\DB\DB;
use Mirarus\DB\Interfaces\Driver\Mysql\Mysql as IMysql;
use PDO;
use PDOException;

class Mysql extends Connect implements IMysql
{

	protected static $sql;
	protected static $tableName;

	/**
	 * @param string $tableName
	 */
	public function from(string $tableName)
	{
		$this->sql = 'SELECT * FROM ' . $tableName;
		$this->tableName = $tableName;
		return $this;
	}

	/**
	 * @param string $tableName
	 */
	public function countFrom(string $tableName)
	{
		$this->sql = 'SELECT  COUNT(*) FROM ' . $tableName;
		$this->tableName = $tableName;
		return $this;
	}

	/**
	 * @param string $columns
	 */
	public function select(string $columns)
	{
		$this->sql = str_replace(' * ', ' ' . $columns . ' ', $this->sql);
		return $this;
	}

	/**
	 * @param array $columns
	 */
	public function all(array $columns = [])
	{
		$result = (array) $this->conn->query($this->sql)->fetchAll(PDO::FETCH_ASSOC);

		DB::setTime(microtime(true), __METHOD__);

		$result['_time'] = DB::getTime(__METHOD__);

		return $result;
	}

	/**
	 * @param  array  $data
	 * @return string
	 */
	private function implodeData(array $data = []): string
	{
		$result = implode(', ', array_map(function ($k, $v) {
			return $k . '="' . $v . '"';
		}, array_keys($data), array_values($data)));

		DB::setTime(microtime(true), __METHOD__);

		return $result;
	}

	/**
	 * @param  array  $columns
	 * @return array
	 */
/*	public function all(array $columns = []): array
	{
		$sql = 'SELECT ' . (implode(',', $columns) ? implode(',', $columns) : '*') . ' FROM ' . $this->tableName;

		$result = (array) $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		DB::setTime(microtime(true), __METHOD__);

		$result['_time'] = DB::getTime(__METHOD__);

		return $result;
	}*/

	/**
	 * @param  array  $data
	 * @return boolean
	 */
	public function insert(array $data = []): bool
	{
		$sql = 'INSERT INTO ' . $this->tableName . ' SET ' . implode(', ', array_map(function ($i) {
			return $i . ' = :' . $i;
		}, array_keys($data)));
		
		$result = (bool) $this->conn->prepare($sql)->execute(array_values($data));

		DB::setTime(microtime(true), __METHOD__);

		return $result;
	}

	/**
	 * @param  array  $sD
	 * @param  array  $wD
	 * @return boolean
	 */
	public function update(array $sD = [], array $wD = []): bool
	{
		$sql = 'UPDATE ' . $this->tableName . ' SET ' . $this->implodeData($sD) . ' WHERE ' . $this->implodeData($wD);

		$result = (bool) $this->conn->query($sql);

		DB::setTime(microtime(true), __METHOD__);

		return $result;
	}
}