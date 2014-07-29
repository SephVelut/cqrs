<?php

namespace spec\Cqrs\Commands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Cqrs\Commands\CommandInflector;

class SimpleCommandBusSpec extends ObjectBehavior
{
	function let()
	{
		$prophet = new \Prophecy\Prophet;

		$commandInflector = $prophet->prophesize('Cqrs\Commands\CommandInflector');

		$this->beConstructedWith($commandInflector->reveal());
	}

	function it_is_initializable()
	{
		$this->shouldHaveType('Cqrs\Commands\CommandBus');
	}

    function it_implements_CommandBus()
    {
        $this->shouldHaveType('Cqrs\Commands\CommandBus');
    }

	function it_fetches_CommandHandler_name_upon_execution(
		CommandInflector $commandInflector,
		$command)
	{
		$commandInflector
			->getHandlerName(new \spec\Cqrs\Commands\IsObjectToken())
			->shouldBeCalled()
			->willReturn('spec\Cqrs\Commands\CommandHandlerDummy');

		$this->beConstructedWith($commandInflector);

		$this->execute($command);

		ob_end_clean();
	}

	function it_calls_handle_on_fetched_CommandHandler_passing_Command(
		CommandInflector $commandInflector)
	{
		$commandHandler = 'spec\Cqrs\Commands\CommandHandlerDummy';
        $command = new DummyCommand;
		$commandInflector
			->getHandlerName(new \spec\Cqrs\Commands\IsObjectToken())
			->shouldBeCalled()
			->willReturn('spec\Cqrs\Commands\CommandHandlerDummy');

		$this->beConstructedWith($commandInflector);

		$this->execute($command)->shouldCallHandlerWith('DummyCommand');
	}

	public function getMatchers()
	{
		return [
			'callHandlerWith' => function($subject, $calledWith) {
				return $this->hasCalledHandle($calledWith);
			}
		];
	}

	private function hasCalledHandle($calledWith)
	{
		$commandHandlerDummyOutput = ob_get_contents();

		ob_end_clean();

        $method = explode(':', explode('| ', $commandHandlerDummyOutput)[0])[1];
        $args = substr(explode(':', explode('| ', $commandHandlerDummyOutput)[1])[1], -12);
		if($method === 'handle' && $args === $calledWith)
			return true;

		return false;
	}
}
