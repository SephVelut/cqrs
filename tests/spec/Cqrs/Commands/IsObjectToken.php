<?php

namespace spec\Cqrs\Commands;

use \Prophecy\Argument\Token\TokenInterface;

class IsObjectToken implements TokenInterface
{
	private $value;

	public function __construct()
	{
		$this->value = null;
	}

	public function scoreArgument($argument)
	{
		if(is_object($argument))
			return 10;

		return false;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function isLast()
	{
		return false;
	}

	public function __toString(){}
}
