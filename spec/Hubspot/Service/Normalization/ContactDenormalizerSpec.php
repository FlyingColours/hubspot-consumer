<?php

namespace spec\Hubspot\Service\Normalization;

use Hubspot\Model\Contact;
use Hubspot\Service\Normalization\ContactDenormalizer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContactDenormalizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ContactDenormalizer::class);
    }

    function it_can_denormalize_contact_payload_to_properties()
    {
        $this
            ->denormalize(
                $payload = [ 'vid' => 1, 'properties' => ['email' => ['value' => 'doctor@tardis']]],
                Contact::class,
                null,
                []
            )
            ->shouldReturnAnInstanceOf(Contact::class);
    }

    function it_can_denormalize_contact_payload_to_properties_given_object_to_populate(Contact $contact)
    {
        $contact->setProperty('email', 'doctor@tardis')->shouldBeCalled();
        $contact->setId(Argument::any())->shouldBeCalled();

        $this
            ->denormalize(
                $payload = [ 'vid' => 1, 'properties' => ['email' => ['value' => 'doctor@tardis']]],
                Contact::class,
                null,
                [ 'object_to_populate' => $contact]
            )
            ->shouldReturn($contact);
    }
}
