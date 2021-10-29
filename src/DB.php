<?php

/**
 * DB
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB;

final class DB implements IDB
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
	 * Connection DSN
	 * @var string
	 */
	private static $dsn;

	/**
	 * Selected Driver
	 * @var string
	 */
	private static $driver;

	/**
	 * Selected Driver Class Name
	 * @var string
	 */
	private static $dClass;

	/**
	 * Selected Driver Class
	 * @var object
	 */
	private static $db;

	/**
	 * @param string|null $driver
	 */
	public function __construct(string $driver = null)
	{
		$this->connect($driver);
	}

	/**
	 * @param string|null $driver
	 */
	public static function connect(string $driver = null)
	{
		if ($driver) self::driver($driver);
		
		self::$time 	= microtime(true);
		self::$driver = Driver::get();
		self::$dClass	= (__NAMESPACE__ . '\\Driver\\' . self::$driver . '\\' . self::$driver);
		self::$db 		= new self::$dClass(self::$dsn);

		$GLOBALS['_DB'] = self::$db;
		$GLOBALS['_DB__' . self::$driver] = self::$db;
		$GLOBALS['_DB__' . @mb_strtolower(self::$driver, "UTF-8")] = self::$db;

		return self::$db;
	}

	/**
	 * @param string $driver
	 */
	public static function driver(string $driver): void
	{
		Driver::set($driver);
	}

	/**
	 * @param toArray ...$dsn
	 */
	public static function dsn(...$dsn): void
	{
		self::$dsn = $dsn;
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
	 */
	public static function getTime(string $func = null)
	{
		return $func ? self::$_time[$func] : self::$_time;
	}

	/**
	 * @return array
	 */
	public function __debugInfo(): array
	{
		return [
			'_driver' => Driver::get(),
			'_dsn' => self::$dsn,
			'_time' => self::$time,
			'_driver_time' => self::getTime()
		];
	}
}