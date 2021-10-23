<?php

/**
 * IDB
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB;

interface IDB
{

	public function __construct(string $driver = null);
	public static function connect(string $driver = null);
	public static function driver(string $driver): void;
	public static function dsn(...$dsn): void;
	public static function setTime(float $time, string $func): void;
	public static function getTime(): array;
	public function __debugInfo(): array;
}