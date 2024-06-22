<?php

/**
 * Connect
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver\BasicDB_Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.4
 */

namespace Mirarus\DB\Driver\BasicDB_Mysql;

use Mirarus\DB\DB;
use Mirarus\DB\Connect as _Connect;
use Mirarus\DB\Interfaces\Driver\BasicDB_Mysql\Connect as IConnect;
use PDO;
use PDOException;

class Connect extends _Connect implements IConnect
{

	protected $conn; // @phpstan-ignore-line
	protected $error; // @phpstan-ignore-line

	public function __construct()
	{
		try {
			$dsn = self::get('dsn');
			if (!$dsn) {
				throw new InvalidArgumentException('DSN information is missing or incorrect.');
			}
			$this->conn = new PDO(...$dsn);
			$this->conn->exec('SET CHARACTER SET utf8mb4');
			$this->conn->exec('SET NAMES utf8mb4');
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		} catch (PDOException $e) {
			static::showError($e); // @phpstan-ignore-line
		} catch (Exception $e) {
			static::showError($e);
		}

		DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
	}

	/**
	 * @return array
	 *
	 * @phpstan-ignore-next-line
	 */
	public function __debugInfo(): array
	{
		return [
			'_connect' => $this->conn,
			'_error' => $this->error
		];
	}
}