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
        $this->getProperties()->shouldHaveCount(0);
        $this->setProperties([['firstName' => 'Doctor']])->shouldReturn($this);
        $this->getProperties()->shouldHaveCount(1);
        $this->addProperty('lastName', 'Who')->shouldReturn($this);
        $this->getProperties()->shouldHaveCount(2);
        $this->removeProperty('lastName')->shouldReturn($this);
        $this->getProperties()->shouldHaveCount(1);
    }
}
