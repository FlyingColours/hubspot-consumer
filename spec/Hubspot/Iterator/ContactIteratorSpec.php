<?php

namespace spec\Hubspot\Iterator;

use Hubspot\Consumer;
use Hubspot\Model\Contact;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hubspot\Iterator\ContactIterator;

class ContactIteratorSpec extends ObjectBehavior
{
    function let(Consumer $consumer)
    {
        $this->beConstructedWith($consumer, ['sms', 'email']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContactIterator::class);
    }

    function it_gets_current(Consumer $consumer)
    {
        $contact = new Contact();
        $consumer->getContacts(Argument::any(), Argument::any())->willReturn(
            new \ArrayObject(['contacts' => [$contact], 'has_more' => false, 'vid_offset' => 345])
        );
        $this->rewind();
        $this->current()->shouldReturn($contact);
    }

    function it_gets_key(Consumer $consumer, Contact $firstContact, Contact $secondContact)
    {
        $contact = new Contact();
        $contact->setId(0);
        $consumer->getContacts(Argument::any(), Argument::any())->willReturn(
            new \ArrayObject(['contacts' => [$contact], 'has_more' => false, 'vid_offset' => 345])
        );
        $this->rewind();
        $this->key()->shouldReturn(0);
    }

    function it_gets_next(Consumer $consumer, Contact $firstContact, Contact $secondContact)
    {
        $firstContact = new Contact();
        $secondContact = new Contact();
        $consumer->getContacts(Argument::any(), Argument::any())->willReturn(
            new \ArrayObject(['contacts' => [$firstContact, $secondContact], 'has_more' => false, 'vid_offset' => 345])
        );
        $this->rewind();
        $this->current()->shouldReturn($firstContact);
        $this->next();
        $this->current()->shouldReturn($secondContact);
    }

    function it_is_valid_if_has_more_contacts(Consumer $consumer, Contact $firstContact, Contact $secondContact)
    {
        $contact = new Contact();
        $consumer->getContacts(Argument::any(), Argument::any())->willReturn(
            new \ArrayObject(['contacts' => [$contact], 'has_more' => false, 'vid_offset' => 345])
        );
        $this->rewind();
        $this->valid()->shouldReturn(true);
    }

    function it_is_not_valid_if_no_more_pages(Consumer $consumer, Contact $firstContact, Contact $secondContact)
    {
        $consumer->getContacts(Argument::any(), Argument::any())->willReturn(
            new \ArrayObject(['contacts' => [], 'has_more' => false, 'vid_offset' => 345])
        );
        $this->rewind();
        $this->valid()->shouldReturn(false);
    }

    function it_is_valid_if_has_next_page(Consumer $consumer, Contact $firstContact)
    {
        $contact = new Contact();
        $consumer->getContacts(Argument::any(), Argument::any())->willReturn(
            new \ArrayObject(['contacts' => [], 'has_more' => true, 'vid_offset' => 345]),
            new \ArrayObject(['contacts' => [$contact], 'has_more' => true, 'vid_offset' => 345])
        );
        $this->rewind();
        $this->valid()->shouldReturn(true);
    }

    function it_is_not_valid_if_not_more_contacts_or_pages(Consumer $consumer, Contact $firstContact)
    {
        $consumer->getContacts(Argument::any(), Argument::any())->willReturn(
            new \ArrayObject(['contacts' => [], 'has_more' => true, 'vid_offset' => 345]),
            new \ArrayObject(['contacts' => [], 'has_more' => false, 'vid_offset' => 345])
        );
        $this->rewind();
        $this->valid()->shouldReturn(false);
    }
}
