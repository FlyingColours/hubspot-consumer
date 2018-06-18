<?php

namespace spec\Hubspot\Listener;

use Buzz\Message\Response;
use Hubspot\Listener\ContactSerializationSubscriber;
use Hubspot\Model\Contact;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Serializer\SerializerInterface;

class ContactSerializationSubscriberSpec extends ObjectBehavior
{
    function let(SerializerInterface $serializer)
    {
        $this->beConstructedWith($serializer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContactSerializationSubscriber::class);
        $this->shouldHaveType(EventSubscriberInterface::class);
    }

    function it_can_deserialize_Contact_obj_from_json_payload(GenericEvent $event, Response $response, Contact $contact)
    {
        $event->getArgument('response')->willReturn($response);
        $event->getSubject()->willReturn($contact);

        $this->onGetContactById($event);
    }

    function it_is_not_so_hard_to_test_static_methods()
    {
        $this->getSubscribedEvents()->shouldNotReturn(null);
    }

    function it_can_deserialize_array_of_contact_objects_from_json_payload(GenericEvent $event, Response $response, Contact $contact, \ArrayObject $subject, SerializerInterface $serializer)
    {
        $response->getContent()->willReturn(
            '{"contacts": [{"email": "foo@bar.com"}], "has-more": true, "vid-offset": 456}'
        );
        $event->getArgument('response')->willReturn($response);
        $event->getSubject()->willReturn($subject);

        $serializer->deserialize(Argument::any(), Argument::any(), Argument::any())->willReturn([]);

        $subject->offsetSet(Argument::exact('has_more'), Argument::exact(true))->shouldBeCalled();
        $subject->offsetSet(Argument::exact('vid_offset'), Argument::exact(456))->shouldBeCalled();
        $subject->offsetSet(Argument::exact('contacts'), Argument::exact([]))->shouldBeCalled();

        $this->onGetContacts($event);
    }
}
