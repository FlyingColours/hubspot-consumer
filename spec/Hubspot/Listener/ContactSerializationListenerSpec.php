<?php

namespace spec\Hubspot\Listener;

use Buzz\Message\Request;
use Buzz\Message\Response;
use Hubspot\Listener\ContactSerializationListener;
use Hubspot\Model\Contact;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Serializer\SerializerInterface;

class ContactSerializationListenerSpec extends ObjectBehavior
{
    function let(SerializerInterface $serializer)
    {
        $this->beConstructedWith($serializer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContactSerializationListener::class);
    }

    function it_does_not_implement_postSend(Request $request, Response $response)
    {
        $this->postSend($request, $response);
    }

    function it_serializes_request_content_to_json_before_sending(Request $request, Contact $contact)
    {
        $request->getContent()->willReturn($contact);

        $request->setContent(Argument::any())->shouldBeCalled();
        $request->addHeader(Argument::any())->shouldBeCalled();

        $this->preSend($request);
    }
}
