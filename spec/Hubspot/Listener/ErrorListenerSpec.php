<?php

namespace spec\Hubspot\Listener;

use Buzz\Listener\ListenerInterface;
use Hubspot\Listener\ErrorListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ErrorListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ErrorListener::class);
        $this->shouldHaveType(ListenerInterface::class);
    }
}
