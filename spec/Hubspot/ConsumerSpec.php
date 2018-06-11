<?php

namespace spec\Hubspot;

use Buzz\Browser;
use Buzz\Message\Response;
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

    function it_can_get_contacts(EventDispatcherInterface $dispatcher, Browser $browser)
    {
        $browser->get(Argument::exact('http://localhost:8080/contacts/v1/lists/all/contacts/all?property=email&property=sms'))->shouldBeCalled();
        $dispatcher->dispatch(Argument::any(), Argument::any())->shouldBeCalled();
        $this->getContacts(['email', 'sms']);
    }

    function it_can_contact_by_id_and_return_null_if_not_found(EventDispatcherInterface $dispatcher, Browser $browser, Response $response)
    {
        $response->getContent()->willReturn('{"status": "error"}');
        $browser->get(Argument::any())->willReturn($response);

        $dispatcher->dispatch(Argument::any(), Argument::any())->shouldBeCalled();

        $this->getContactById($id = '1234')->shouldReturn(null);
    }

    function it_can_contact_by_email(EventDispatcherInterface $dispatcher, Browser $browser)
    {
        $browser->get(Argument::any())->shouldBeCalled();

        $dispatcher->dispatch(Argument::any(), Argument::any())->shouldBeCalled();

        $this->getContactByEmail($email = 'doctor@tardis')->shouldReturnAnInstanceOf(Contact::class);
    }

    function it_can_create_contact_on_Hubspot(Browser $browser)
    {
        $browser->post(Argument::any(), Argument::any(), Argument::any())->shouldBeCalled();

        $this->createContact($payload = [ 'email' => 'doctor@tardis' ]);
    }

    function it_can_update_contact_on_Hubspot(Browser $browser)
    {
        $browser->post(Argument::any(), Argument::any(), Argument::any())->shouldBeCalled();

        $this->updateContact($email = 'doctor@tardis', $payload = [ 'email' => 'doctor@tardis' ]);
    }
}
