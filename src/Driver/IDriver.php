<?php

/**
 * IDriver
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB\Driver;

interface IDriver
{

	public function __construct(array $dsn);
	public function __debugInfo(): array;
	public static function insert(string $table, array $data): bool;
}