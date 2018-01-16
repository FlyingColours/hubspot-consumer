<?php

namespace spec\Hubspot\Service\Normalization;

use Hubspot\Model\Contact;
use Hubspot\Service\Normalization\ContactNormalizer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContactNormalizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ContactNormalizer::class);
    }

    function it_can_workout_if_it_supportsNormalization(Contact $contact)
    {
        $this->supportsNormalization($contact)->shouldReturn(true);

        $this->supportsNormalization([])->shouldReturn(false);
        $this->supportsNormalization(new \stdClass())->shouldReturn(false);
    }

    function it_can_normalize_contact_or_array_to_array_format_require_by_API()
    {
        $this->normalize(['email' => 'doctor@tardis'])->shouldNotReturn(null);
    }
}
