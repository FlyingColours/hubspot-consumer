<?php

namespace spec\Hubspot\Listener;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Hubspot\Listener\HapiListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HapiListenerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith($hapi = '1234');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HapiListener::class);
        $this->shouldHaveType(ListenerInterface::class);
    }

    function it_postSend_does_not_do_anything(Request $request, Response $response)
    {
        $this->postSend($request, $response);
    }

    function it_preSend_appends_hapi_key_to_the_query_parameters(Request $request)
    {
        $request->getResource()->willReturn('http://localhost/hello?test=1');
        $request->setResource('http://localhost/hello?test=1&hapi=1234')->shouldBeCalled();
        $this->preSend($request);
    }
}
