<?php

namespace Cqrs\Commands;

class SimpleCommandInflector implements CommandInflector
{
	public function getHandlerName($command)
	{
		if(!is_object($command) || is_callable($command))
			throw new \InvalidArgumentException;

		if(!$this->isCommand($command))
			throw new \InvalidArgumentException( "Object must have \'Command\' at end of name");

		$className = get_class($command);
		$pos = strrpos($className, 'Command');

		return substr_replace($className, 'Handler', $pos, strlen('Command'));
	}

	private function isCommand($object)
	{
		return substr(get_class($object), -7) === "Command";
	}
}
