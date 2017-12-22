<?php

namespace Hubspot\Service\Normalization;

use Hubspot\Model\Contact;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ContactNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = [])
    {
        $normalized = ['properties' => []];

        foreach($object as $property => $value)
        {
            $normalized['properties'][] = ['property' => $property, 'value' => $value ];
        }

        return $normalized;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Contact || is_array($data);
    }
}
