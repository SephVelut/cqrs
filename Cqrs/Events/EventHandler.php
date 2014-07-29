<?php

namespace Cqrs\Events;

Interface EventHandler
{
	public function handle();
}
