<?php

/**
 * DB
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB\Interfaces;

interface DB
{

	public function __construct($driver, ...$dsn);
	public static function setTime(float $time, string $func): void;
	public static function getTime(string $func = null);
	public function __call(string $method, array $args = []);
	public static function __callStatic(string $method, array $args = []);
	public function __debugInfo(): array;
}