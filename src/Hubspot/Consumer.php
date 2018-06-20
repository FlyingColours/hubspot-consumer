<?php

namespace Hubspot;

use Buzz\Browser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Hubspot\Model\Contact;
use Hubspot\Iterator\ContactIterator;

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
     * Returns iterable of all contacts
     * @param array $properties
     * @return ContactIterator
     */
    public function getAllContacts(array $properties = []): ContactIterator
    {
        return new ContactIterator(
            $this, $properties
        );
    }

    /**
     * Gets a page of contacts. @see self::getAllContacts() to get all contacts
     * @param array $properties Properties to populate the Contact object with
     * @param array $args
     * @return array['contacts', 'vid_offset', 'has_more']
     */
    public function getContacts(array $properties = [], $args = []): \ArrayObject
    {
        $url = sprintf(
            '%s/contacts/v1/lists/all/contacts/all%s',
            $this->apiUrl,
            ($properties ? '?property=' : ''). implode($properties, '&property=')
        );

        if ($args) {
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . http_build_query($args);
        }

        $response = $this->browser->get($url);

        $event = new GenericEvent(new \ArrayObject(), [ 'response' => $response ]);

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
