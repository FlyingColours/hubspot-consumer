<?php

namespace Hubspot;

use Buzz\Browser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Hubspot\Model\Contact;

class Consumer
{
    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /** @var Browser */
    protected $browser;

    /** @var string */
    protected $apiUrl;

    /**
     * Consumer constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param Browser $browser
     * @param string $apiUrl
     */
    public function __construct(EventDispatcherInterface $dispatcher, Browser $browser, string $apiUrl)
    {
        $this->dispatcher = $dispatcher;
        $this->browser = $browser;
        $this->apiUrl = $apiUrl;
    }

    /**
     * @todo need to fetch next
     * @param array $properties The properties to propagate for each contact
     * @return mixed
     */
    public function getContacts(array $properties = []): \ArrayObject
    {
        $url = sprintf(
            '%s/contacts/v1/lists/all/contacts/all%s',
            $this->apiUrl,
            (count($properties) ? '?property=' : ''). implode($properties, '&property=')
        );

        $response = $this->browser->get($url);

        $contacts = new \ArrayObject();
        $event = new GenericEvent($contacts, [ 'response' => $response ]);

        $this->dispatcher->dispatch(__METHOD__, $event);

        return $event->getSubject();
    }

    /**
     * @param string $id
     * @return Contact|null
     */
    public function getContactById(string $id): ?Contact
    {
        $response = $this->browser
            ->get(sprintf('%s/contacts/v1/contact/vid/%s/profile', $this->apiUrl, $id))
        ;

        $event = new GenericEvent(new Contact(), [ 'response' => $response ]);

        $this->dispatcher->dispatch(__METHOD__, $event);

        return $event->getSubject()->getEmail() ? $event->getSubject() : null;
    }

    public function getContactByEmail($email)
    {
        $response = $this->browser
            ->get(sprintf('%s/contacts/v1/contact/email/%s/profile', $this->apiUrl, $email))
        ;

        $event = new GenericEvent(new Contact(), [ 'response' => $response ]);

        $this->dispatcher->dispatch(__METHOD__, $event);

        return $event->getSubject();
    }

    /**
     * @param array|Contact $payload
     *
     * @return Contact
     */
    public function createContact($payload)
    {
        $response = $this->browser
            ->post(sprintf('%s/contacts/v1/contact/', $this->apiUrl), [], $payload)
        ;

        $event = new GenericEvent(new Contact(), [ 'response' => $response ]);

        $this->dispatcher->dispatch(__METHOD__, $event);

        return $event->getSubject();
    }

    public function updateContact($emailAddress, $payload)
    {
        $this->browser
            ->post(sprintf('%s/contacts/v1/contact/email/%s/profile', $this->apiUrl, $emailAddress), [], $payload)
        ;
    }
}
