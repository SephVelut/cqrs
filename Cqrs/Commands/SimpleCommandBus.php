<?php

namespace Cqrs\Commands;

class SimpleCommandBus implements CommandBus
{
	private $inflector;

	public function __construct(CommandInflector $inflector)
	{
		$this->inflector = $inflector;
	}

	public function execute($command)
	{
		$handlerName = $this->inflector->getHandlerName($command);

		$handler = new $handlerName;

		$handler->handle($command);
	}
}
