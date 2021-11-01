<?php

/**
 * Driver
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace Mirarus\DB;

use Mirarus\DB\Interfaces\Driver as IDriver;
use Exception;

class Driver implements IDriver
{

	/**
	 * @var array
	 */
	private static $drivers = [
		'mysql' => 'Mysql',
		'mongodb' => 'MongoDB',
		'basicdb-mysql' => 'BasicDB_Mysql'
	];
	
	/**
	 * @var string
	 */
	private static $driver;

	/**
	 * @param string $driver
	 */
	public static function set(string $driver): void
	{
		$driver = @trim($driver);
		$driver = @mb_strtolower($driver, "UTF-8");
		$driver = @self::$drivers[$driver];

		if (!$driver) throw new Exception('Undefined SQL Driver');

		self::$driver = $driver ? $driver : null;
	}
	
	public static function get()
	{
		return self::$driver;
	}
}