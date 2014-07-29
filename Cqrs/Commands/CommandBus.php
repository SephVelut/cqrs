<?php

namespace Cqrs\Commands;

interface CommandBus
{
	public function execute($command);
}
