<?php

/**
 * MongoDB
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver\MongoDB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Driver\MongoDB;

use Mirarus\DB\DB;
use Mirarus\DB\Driver\IDriver;
use MongoDB\Driver\Manager;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Query;
use MongoDB\Driver\Exception\ConnectionException;

class MongoDB implements IDriver
{
	
	private static $dbName;
	private static $conn;
	private static $error;

	/**
	 * @param array $dsn
	 */
	public function __construct(array $dsn)
	{
		try {
			self::$dbName = array_pop($dsn);
			self::$conn = new Manager(...$dsn);
		} catch (ConnectionException $e) {
			self::$error = $e;
		}

		DB::setTime(microtime(true), __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function __debugInfo(): array
	{
		return [
			'_connect' => self::$conn,
			'_error' => self::$error,
			'_time' => DB::getTime()
		];
	}

	/**
	 * @param  string $table
	 * @param  array  $columns
	 * @return array
	 */
	public static function all(string $table, array $columns = []): array
	{
		$query = new Query([]);
		$result = self::$conn->executeQuery(self::$dbName . '.' . $table, $query)->toArray();

		DB::setTime(microtime(true), __FUNCTION__);

		$result['_time'] = DB::getTime(__FUNCTION__);

		return $result;
	}

	/**
	 * @param  string $table
	 * @param  array  $data
	 * @return boolean
	 */
	public static function insert(string $table, array $data = []): bool
	{
		$writter = new BulkWrite();
		$writter->insert($data);

		$result = (bool) self::$conn->executeBulkWrite(self::$dbName . '.' . $table, $writter);

		DB::setTime(microtime(true), __FUNCTION__);

		return $result;
	}

	/**
	 * @param  string $table
	 * @param  array  $sD
	 * @param  array  $wD
	 * @return boolean
	 */
	public static function update(string $table, array $sD = [], array $wD = []): bool
	{
		$writter = new BulkWrite();
		$writter->update($wD, ['$set' => $sD]);

		$result = (bool) self::$conn->executeBulkWrite(self::$dbName . '.' . $table, $writter);

		DB::setTime(microtime(true), __FUNCTION__);

		return $result;
	}
}