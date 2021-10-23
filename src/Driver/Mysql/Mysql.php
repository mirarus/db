<?php

/**
 * Mysql
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver\Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB\Driver\Mysql;

use Mirarus\DB\DB;
use Mirarus\DB\Driver\IDriver;
use PDO;
use PDOException;

class Mysql implements IDriver
{

	private static $conn;
	private static $error;

	/**
	 * @param array $dsn
	 */
	public function __construct(array $dsn)
	{
		try {

			self::$conn = new PDO(...$dsn);
			self::$conn->query('SET CHARACTER SET utf8');
			self::$conn->query('SET NAMES utf8 utf8');
			self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		} catch (PDOException $e) {
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
	 * @param  array  $data
	 * @return boolean
	 */
	public static function insert(string $table, array $data): bool
	{
		$sql = 'INSERT INTO ' . $table . ' SET ' . implode(', ', array_map(function ($i) {
			return $i . ' = :' . $i;
		}, array_keys($data)));
		
		$result = (bool) self::$conn->prepare($sql)->execute(array_values($data));

		DB::setTime(microtime(true), __FUNCTION__);

		return $result;
	}
}