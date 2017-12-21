<?php

namespace spec\Hubspot\Model;

use Hubspot\Model\ContactList;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContactListSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ContactList::class);
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn(null);
        $this->setId('123232')->shouldReturn($this);
        $this->getId()->shouldNotReturn(null);
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn(null);
        $this->setName('Unqualified Leads')->shouldReturn($this);
        $this->getName()->shouldNotReturn(null);
    }

    function it_knows_if_it_is_dynamic()
    {
        $this->isDynamic()->shouldReturn(true);
        $this->setDynamic(false)->shouldReturn($this);
        $this->isDynamic()->shouldReturn(false);
    }
}
