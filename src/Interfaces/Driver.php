<?php

/**
 * Driver
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Interfaces;

interface Driver
{

	public static function get(): array; // @phpstan-ignore-line
}