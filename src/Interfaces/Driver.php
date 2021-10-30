<?php

/**
 * Driver
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB\Interfaces;

interface Driver
{

	public static function set(string $driver): void;
	public static function get();
}