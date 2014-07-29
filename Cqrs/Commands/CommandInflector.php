<?php

namespace Cqrs\Commands;

interface CommandInflector
{
	public function getHandlerName($command);
}
