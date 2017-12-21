<?php

namespace Hubspot;

use Buzz\Browser;
use Hubspot\Model\Contact;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

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
     * @param string $id
     *
     * @return Contact
     * @see https://developers.hubspot.com/docs/methods/contacts/get_contact
     */
    public function getContactById(string $id): Contact
    {
        $response = $this->browser
            ->get(sprintf('%s/contacts/v1/contact/vid/%s/profile', $this->apiUrl, $id))
        ;

        print_r($response);

        $event = new GenericEvent(new Contact(), [ 'response' => $response ]);

        $this->dispatcher->dispatch(__METHOD__, $event);

        return $event->getSubject();
    }

    public function createContact(array $payload)
    {
        $this->browser
            ->post(sprintf('%s/contacts/v1/contact/', $this->apiUrl), null, $payload)
        ;
    }
}
