<?php

/**
 * Mysql
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver\Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Driver\Mysql;

use Mirarus\DB\DB;
use Mirarus\DB\Driver\IDriver;
use PDO;
use PDOException;

class Mysql 
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
	 * @param  array  $data
	 * @return string
	 */
	private static function implodeData(array $data = []): string
	{
		$result = implode(', ', array_map(function ($k, $v) {
			return $k . '="' . $v . '"';
		}, array_keys($data), array_values($data)));

		DB::setTime(microtime(true), __FUNCTION__);

		return $result;
	}

	/**
	 * @param  string $table
	 * @param  array  $columns
	 * @return array
	 */
	public static function all(string $table, array $columns = []): array
	{
		$sql = 'SELECT ' . (implode(',', $columns) ? implode(',', $columns) : '*') . ' FROM ' . $table;

		$result = (array) self::$conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

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
		$sql = 'INSERT INTO ' . $table . ' SET ' . implode(', ', array_map(function ($i) {
			return $i . ' = :' . $i;
		}, array_keys($data)));
		
		$result = (bool) self::$conn->prepare($sql)->execute(array_values($data));

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
		$sql = 'UPDATE ' . $table . ' SET ' . self::implodeData($sD) . ' WHERE ' . self::implodeData($wD);

		$result = (bool) self::$conn->query($sql);

		DB::setTime(microtime(true), __FUNCTION__);

		return $result;
	}
}