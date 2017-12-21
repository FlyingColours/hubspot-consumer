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
}
