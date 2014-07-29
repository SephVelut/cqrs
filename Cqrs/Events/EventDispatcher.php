<?php

namespace Cqrs\Events;

use Cqrs\AggregateRoot;

class EventDispatcher
{
	private $subscribedEvents = [];

    public function dispatch(array $events)
    {
		$eventsThatHaveSubscribed = $this->getSubscribersForEvents($events);

        if(empty($events) || empty($this->getSubscribers()) || empty($eventsThatHaveSubscribed))
			return false;

		foreach($eventsThatHaveSubscribed as $event)
				foreach($this->subscribedEvents[get_class($event)] as $eventHandler)
					$eventHandler->handle($event);
    }

    public function subscribe($event, EventHandler $eventHandler)
    {
		$this->subscribedEvents[$event][] = $eventHandler;
    }

    public function getSubscribers()
    {
		return $this->subscribedEvents;
    }

    private function getSubscribersForEvents($events)
    {
        return array_filter($events, function($event) {
            if(array_key_exists(get_class($event), $this->subscribedEvents))
                return true;
        });
    }
}
