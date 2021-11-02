<?php

/**
 * _Exception
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB;

use Exception;

class _Exception extends Exception
{

	protected $class;
	protected $message;

	/**
	 * @param string $class
	 * @param string $message
	 */
	public function __construct(string $class, string $message)
	{
		$this->class = $class;
		$this->message = $message;

    parent::__construct($message);
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return "[" . $this->class . "] | " . $this->message;
	}
}