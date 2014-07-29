<?php

namespace spec\Cqrs\Commands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Cqrs\Commands\DummyCommand;

class SimpleCommandInflectorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Cqrs\Commands\SimpleCommandInflector');
    }

    function it_implements_CommandInflector()
    {
        $this->shouldImplement('Cqrs\Commands\CommandInflector');
    }

    function it_accepts_only_class_objects()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringGetHandlerName(null);
        $this->shouldThrow('\InvalidArgumentException')->duringGetHandlerName(1);
        $this->shouldThrow('\InvalidArgumentException')->duringGetHandlerName('a');
        $this->shouldThrow('\InvalidArgumentException')->duringGetHandlerName([]);
        $this->shouldThrow('\InvalidArgumentException')->duringGetHandlerName(function () {});

		$command = new DummyCommand;
        $this->getHandlerName($command);
    }

    function it_only_accepts_Commands(CommandItIsNot $notACommand2)
    {
        $this->shouldThrow(
            new \InvalidArgumentException("Object must have \'Command\' at end of name"))
            ->duringGetHandlerName($notACommand2);
    }

    function it_translates_Command_object_to_CommandHandler_name()
    {
        $command = new DummyCommand;
        $commandTwo = new DummyTwoCommand;

        $commandNamespace = explode('\\', get_class($command));
        array_pop($commandNamespace);
        $commandNamespace = implode('\\', $commandNamespace);

        $this->getHandlerName($command)->shouldReturn($commandNamespace . '\\DummyHandler');
        $this->getHandlerName($commandTwo)->shouldReturn($commandNamespace . '\\DummyTwoHandler');
    }
}
