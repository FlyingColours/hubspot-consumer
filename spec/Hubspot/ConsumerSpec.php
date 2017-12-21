<?php

namespace spec\Hubspot;

use Buzz\Browser;
use Hubspot\Consumer;
use Hubspot\Model\Contact;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ConsumerSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $dispatcher, Browser $browser)
    {
        $this->beConstructedWith($dispatcher, $browser, 'http://localhost:8080');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Consumer::class);
    }

    function it_can_contact_by_id(EventDispatcherInterface $dispatcher, Browser $browser)
    {
        $browser->get(Argument::any())->shouldBeCalled();

        $dispatcher->dispatch(Argument::any(), Argument::any())->shouldBeCalled();

        $this->getContactById($id = '1234')->shouldReturnAnInstanceOf(Contact::class);
    }

    function it_can_create_contact_on_Hubspot(Browser $browser)
    {
        $browser->post(Argument::any(), null, Argument::any())->shouldBeCalled();

        $this->createContact($payload = [ 'email' => 'doctor@tardis' ]);
    }
}
