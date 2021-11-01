<?php

/**
 * Driver
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.3
 */

namespace Mirarus\DB;

class Driver
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
	 * @return array
	 */
	public static function get(): array
	{
		return (array) self::$drivers;
	}
}