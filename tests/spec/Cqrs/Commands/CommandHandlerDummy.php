<?php

namespace spec\Cqrs\Commands;

class CommandHandlerDummy
{
	public function __call($name, $args)
	{
		ob_start();

		printf('method:%s| args:%s| class:%s',
			$name, get_class($args[0]), __CLASS__);
	}
}
