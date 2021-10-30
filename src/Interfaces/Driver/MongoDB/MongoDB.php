<?php

/**
 * MongoDB
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces\Driver\MongoDB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB\Interfaces\Driver\MongoDB;

interface MongoDB
{

	public function from(string $tableName);
	public function all(array $columns = []): array;
	public function insert(array $data = []): bool;
	public function update(array $sD = [], array $wD = []): bool;
}