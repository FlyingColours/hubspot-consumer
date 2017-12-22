<?php

namespace Hubspot\Service\Normalization;

use Hubspot\Model\Contact;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ContactDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        /** @var Contact $contact */
        $contact = $context['object_to_populate'] ?? new $class;

        $contact->setId($data['vid']);

        foreach ($data['properties'] as $propertyName => $item)
        {
            $contact->setProperty($propertyName, $item['value']);
        }

        return $contact;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return isset($data['properties']) && isset($data['vid']);
    }
}
