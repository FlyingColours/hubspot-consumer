<?php

namespace Hubspot\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Serializer\SerializerInterface;

class ContactSerializationSubscriber implements EventSubscriberInterface
{
    /** @var SerializerInterface */
    protected $serializer;

    /**
     * ContactSerializationSubscriber constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Hubspot\Consumer::getContactById' => [ 'onGetContactById' ],
            'Hubspot\Consumer::createContact' => [ 'onGetContactById' ]
        ];
    }

    public function onGetContactById(GenericEvent $event)
    {
        $payload = $event->getArgument('response')->getContent();
        $contact = $event->getSubject();

        $this->serializer->deserialize($payload, get_class($contact), 'json', [ 'object_to_populate' => $contact ]);
    }
}
