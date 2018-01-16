<?php

namespace spec\Hubspot\Model;

use Hubspot\Model\Contact;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContactSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Contact::class);
    }

    function it_has_a_unique_id()
    {
        $this->getId()->shouldReturn(null);
        $this->setId('3234574')->shouldReturn($this);
        $this->getId()->shouldNotReturn(null);
    }

    function it_has_properties()
    {
        $this->getArrayCopy()->shouldHaveCount(0);
        $this->setProperty('lastName', 'Who')->shouldReturn($this);
        $this->getArrayCopy()->shouldHaveCount(1);
        $this->removeProperty('lastName')->shouldReturn($this);
        $this->getArrayCopy()->shouldHaveCount(0);
    }

    function it_can_set_email_although_it_is_a_just_another_property_aka_alias()
    {
        $this->getEmail()->shouldReturn(null);
        $this->setProperty('email', 'doctor@tardis')->shouldReturn($this);
        $this->getEmail()->shouldReturn('doctor@tardis');
        $this->setEmail('doctor@tardis.local')->shouldReturn($this);
        $this->getProperty('email')->shouldReturn('doctor@tardis.local');
    }

    function it_implements_ArrayAccess_for_properties()
    {
        $this->setProperty('email', 'doctor@tardis')->shouldReturn($this);
        $this->offsetGet('email')->shouldReturn('doctor@tardis');
    }
}
