<?php

namespace spec\Hubspot\Listener;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Hubspot\Listener\ErrorListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ErrorListener::class);
        $this->shouldHaveType(ListenerInterface::class);
    }

    function it_does_not_implement_preSend(Request $request)
    {
        $this->preSend($request);
    }

    function it_handles_http_errors_and_converts_them_to_exceptions(Request $request, Response $response)
    {
        $response->isSuccessful()->willReturn(true);
        $this->postSend($request, $response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(401);
        $response->getReasonPhrase()->willReturn('Unauthorized');

        $this
            ->shouldThrow(HttpException::class)
            ->during('postSend', [$request, $response])
        ;
    }
}
