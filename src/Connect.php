<?php

/**
 * Connect
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB;

use Mirarus\DB\Interfaces\Connect as IConnect;

class Connect implements IConnect
{

	/**
	 * Connection Link
	 * @var array
	 */
	private static $link = [];

	public function __construct()
	{
		$args = func_get_args();

		if ($args) {

			$driver = array_shift($args);
			if ($driver && $args) self::set($driver, ...$args);
		}
	}

	/**
	 * @param string $driver
	 */
	public static function driver(string $driver): void
	{
		Driver::set($driver);
		self::$link['driver'] = Driver::get();
	}

	/**
	 * @param array $dsn
	 */
	public static function dsn(...$dsn): void
	{
		self::$link['dsn'] = is_array($dsn[0]) ? $dsn[0] : $dsn;
	}

	/**
	 * @param string $driver
	 * @param array  $dsn
	 */
	public static function set(string $driver, ...$dsn): void
	{
		self::driver($driver);
		self::dsn($dsn);
	}

	/**
	 * @param string|null $par
	 */
	public static function get(string $par = null)
	{
		return $par ? self::$link[$par] : self::$link;
	}

	/**
	 * @return array
	 */
	public function __debugInfo(): array
	{
		return self::$link;
	}
}