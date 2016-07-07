<?php
/**
 * Created by PhpStorm.
 * User: anik
 * Date: 7/7/16
 * Time: 3:38 PM
 */

namespace Anik\Generator;

class RepositoryGeneratorException extends \Exception
{
	public function __construct ($message)
	{
		parent::__construct($message);
	}
}