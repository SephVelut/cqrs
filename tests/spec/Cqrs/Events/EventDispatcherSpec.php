<?php

namespace spec\Cqrs\Events;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventDispatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Cqrs\Events\EventDispatcher');
    }

	function it_can_subscribe_EventHandlers_to_Event( \Cqrs\Events\EventHandler $eventHandler)
	{
		$event = 'EventHasHappened';

		$this->subscribe($event, $eventHandler);
	}

	function it_will_give_a_list_of_subscribers()
	{
		$this->getSubscribers();
	}

	function it_can_subscribe_multiple_event_handlers_to_multiple_events(\Cqrs\Events\EventHandler $eventHandler)
	{
		$events = ['EventHasHappened', 'EventTwoHasHappened'];

		$this->testSubscribe($eventHandler, $events);

		$this->getSubscribers()->shouldReturn([$events[0] => [$eventHandler], $events[1] => [$eventHandler]]);
	}

	function it_will_not_dispatch_events_if_subscriber_list_is_empty($event)
	{
		$this->dispatch([$event])->shouldReturn(false);
	}

	function it_will_not_dispatch_events_if_events_are_not_available(\Cqrs\Events\EventHandler $eventHandler)
	{
		$this->subscribe('event_dummy', $eventHandler);

		$eventHandler->handle()->shouldNotBeCalled();

		$this->dispatch([])->shouldReturn(false);
	}

    function it_will_not_dispatch_events_if_no_subscribers_match_events(
        \Cqrs\Events\EventHandler $eventHandler,
        $event, $event2)
    {
        $this->subscribe(get_class($event->getWrappedObject()), $eventHandler);

        $this->dispatch([$event2])->shouldReturn(false);
    }

    function it_will_dispatch_events_if_at_least_one_or_more_subscribers_match_event(
        \Cqrs\Events\EventHandler $eventHandler,
        $event, $event2
    )
    {
        $this->subscribe(get_class($event->getWrappedObject()), $eventHandler);

        $this->dispatch([$event, $event2])->shouldNotReturn(false);
    }

	function it_will_invoke_EventHandlers_for_associated_Events(
		\Cqrs\Events\EventHandler $eventHandler,
		\Cqrs\Events\EventHandler $eventHandler2,
		$event, $event2)
	{
		$eventName = get_class($event->getWrappedObject());
		$eventName2 = get_class($event2->getWrappedObject());

		$this->subscribe($eventName, $eventHandler);
		$this->subscribe($eventName2, $eventHandler);
		$this->subscribe($eventName, $eventHandler2);

		$this->dispatch([$event, $event2]);

		$eventHandler->handle($event->getWrappedObject())->shouldBeCalled();
		$eventHandler->handle($event2->getWrappedObject())->shouldBeCalled();
		$eventHandler2->handle($event->getWrappedObject())->shouldBeCalled();
	}

	function it_will_NOT_invoke_subscribed_EventHandler_if_its_event_is_unavailable(
		\Cqrs\Events\EventHandler $eventHandler,
		$event, $event2)
	{
		$this->subscribe(get_class($event->getWrappedObject()), $eventHandler);

		$this->dispatch([$event2]);

		$eventHandler->handle(Argument::any())->shouldNotBeCalled();
	}

	private function testSubscribe($eventHandler, $events)
	{
		foreach($events as $event) {
			$this->subscribe($event, $eventHandler);
		}
	}
}
