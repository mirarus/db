<?php

/**
 * Driver
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.4
 */

namespace Mirarus\DB;

class Driver
{

	/**
	 * @var array
	 */
	private static $drivers = [ // @phpstan-ignore-line
		'mysql' => 'Mysql',
		'mongodb' => 'MongoDB',
		'basicdb-mysql' => 'BasicDB_Mysql'
	];

	/**
	 * @var string
	 */
	private static $driver;

	/**
	 * @return array
	 */
	public static function drivers(): array // @phpstan-ignore-line
	{
		return (array) self::$drivers;
	}

	/**
	 * @param string $driver
	 */
	public static function set(string $driver): void
	{
		if (self::$drivers[$driver]) self::$driver = $driver;
	}

	/**
	 * @return string
	 */
	public static function get(): string
	{
		return (string) self::$drivers[self::$driver];
	}

	/**
	 * @return string
	 */
	public static function getText(): string
	{
		return (string) str_replace(['-', '_'], ' ', self::get());
	}
}