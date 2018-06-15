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
            'Hubspot\Consumer::getContactByEmail' => [ 'onGetContactById' ],
            'Hubspot\Consumer::createContact' => [ 'onGetContactById' ],
            'Hubspot\Consumer::getContacts' => [ 'onGetContacts' ]
        ];
    }

    public function onGetContactById(GenericEvent $event)
    {
        $payload = $event->getArgument('response')->getContent();
        $contact = $event->getSubject();
        $this->serializer->deserialize($payload, get_class($contact), 'json', [ 'object_to_populate' => $contact ]);
    }

    public function onGetContacts(GenericEvent $event)
    {
        $payload = $event->getArgument('response')->getContent();
        $subject = $event->getSubject();

        $response = json_decode($payload, true);

        $contactsArr = $response['contacts'];

        $contacts = $this->serializer->deserialize(
            json_encode($contactsArr),
            'Hubspot\Model\Contact[]',
            'json'
        );

        $subject['has_more'] = $response['has-more'];
        $subject['vid_offset'] = $response['vid-offset'];
        foreach ($contacts as $c) {
            $subject['contacts'][] = $c;
        }
    }
}
