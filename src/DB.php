<?php

/**
 * DB
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.3
 */

namespace Mirarus\DB;

use Mirarus\DB\Interfaces\DB as IDB;
use Mirarus\DB\Interfaces\Connect as IConnect;

@set_time_limit(0);

class DB implements IDB
{

	/**
	 * Class Time
	 * @var float
	 */
	private static $time = 0.0;

	/**
	 * Driver Time
	 * @var array
	 */
	private static $_time = [];

	/**
	 * Connection Link
	 * @var array
	 */
	protected static $connect = [];

	/**
	 * Selected Driver Namespace Name
	 * @var string
	 */	
	private static $dNamespace;

	/**
	 * Selected Driver Class Name
	 * @var string
	 */
	private static $dClass;

	/**
	 * Selected Driver Class
	 * @var class
	 */
	private static $db;

	/**
	 * @param IConnect $connector
	 */
	public function __construct(IConnect $connector)
	{
		self::$time				= microtime(true);
		self::$connect		= $connector->get();
		self::$dNamespace = (__NAMESPACE__ . '\\Driver\\' . self::$connect['driver']);
		self::$dClass			= (self::$dNamespace . '\\' . self::$connect['driver']);
		self::$db					= new self::$dClass();

		$GLOBALS['_DB']		= self::$db;
		$GLOBALS['_DB__' . self::$connect['driver']] = self::$db;
		$GLOBALS['_DB__' . @mb_strtolower(self::$connect['driver'], "UTF-8")] = self::$db;

		self::setTime(microtime(true), __METHOD__);

		return self::$db;
	}

	/**
	 * @param float  $time
	 * @param string $func
	 */
	public static function setTime(float $time, string $func): void
	{
		self::$_time[$func] = ($time - self::$time);
	}

	/**
	 * @param string|null $func
	 * @param string|null $ns
	 */
	public static function getTime(string $func = null, string $ns = null)
	{
		if ($ns) {
			foreach (self::$_time as $key => $val) {
				return strstr($key, $ns) ? [$key => $val] : null;
			}
		}

		return $func ? self::$_time[$func] : self::$_time;
	}

	/**
	 * @param string $method
	 * @param array  $args
	 */
	public function __call(string $method, array $args = [])
	{
		$function = call_user_func_array([self::$db, $method], $args);
		self::setTime(microtime(true), __METHOD__);
		return $function;
	}

	/**
	 * @param string $method
	 * @param array  $args
	 */
	public static function __callStatic(string $method, array $args = [])
	{
		$function = call_user_func_array([self::$db, $method], $args);
		self::setTime(microtime(true), __METHOD__);
		return $function;
	}

	/**
	 * @return array
	 */
	public function __debugInfo(): array
	{
		return [
			'_driver' => self::$connect['driver'],
			'_dsn' => self::$connect['dsn'],
			'_db' => self::$db,
			'_time' => self::getTime()
		];
	}
}