<?php

namespace spec\Hubspot\Listener;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Hubspot\Listener\HapiListener;
use PhpSpec\ObjectBehavior;

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
        $request->setResource('http://localhost/hello?test=1&hapikey=1234')->shouldBeCalled();
        $this->preSend($request);
    }

    function it_keeps_duplicate_query_parameters_on_preSend(Request $request)
    {
        $request->getResource()->willReturn('http://localhost/hello?test=foo&test=bar');
        $request->setResource('http://localhost/hello?test=foo&test=bar&hapikey=1234')->shouldBeCalled();
        $this->preSend($request);
    }
}
