<?php

/**
 * MongoDB
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver\MongoDB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace Mirarus\DB\Driver\MongoDB;

use Mirarus\DB\DB;
use Mirarus\DB\Interfaces\Driver\MongoDB\MongoDB as IMongoDB;
use MongoDB\Driver\Query;
use MongoDB\Driver\BulkWrite;

class MongoDB extends Connect implements IMongoDB
{

	/**
	 * @var string
	 */
	private static $tableName;

	/**
	 * @param string $tableName
	 */
	public function from(string $tableName) // @phpstan-ignore-line
	{
		$this->tableName = $tableName;
		return $this;
	}

	/**
	 * @param  array $columns
	 * @return array
	 */
	public function all(array $columns = []): array // @phpstan-ignore-line
	{
		$query = new Query([]);
		$result = $this->conn->executeQuery($this->dbName . '.' . $this->tableName, $query)->toArray();

		DB::setTime(microtime(true), __METHOD__);

		$result['_time'] = DB::getTime(__METHOD__);

		return $result;
	}

	/**
	 * @param  array  $data
	 * @return boolean
	 */
	public function insert(array $data = []): bool // @phpstan-ignore-line
	{
		$writter = new BulkWrite();
		$writter->insert($data);

		$result = (bool) $this->conn->executeBulkWrite($this->dbName . '.' . $this->tableName, $writter);

		DB::setTime(microtime(true), __METHOD__);

		return $result;
	}

	/**
	 * @param  array  $sD
	 * @param  array  $wD
	 * @return boolean
	 */
	public function update(array $sD = [], array $wD = []): bool // @phpstan-ignore-line
	{
		$writter = new BulkWrite();
		$writter->update($wD, ['$set' => $sD]);

		$result = (bool) $this->conn->executeBulkWrite($this->dbName . '.' . $this->tableName, $writter);

		DB::setTime(microtime(true), __METHOD__);

		return $result;
	}
}