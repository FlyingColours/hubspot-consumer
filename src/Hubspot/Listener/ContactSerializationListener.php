<?php

namespace Hubspot\Listener;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;
use Hubspot\Model\Contact;
use Symfony\Component\Serializer\SerializerInterface;

class ContactSerializationListener implements ListenerInterface
{
    /** @var SerializerInterface */
    protected $serializer;

    /**
     * ContactSerializationListener constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function preSend(RequestInterface $request)
    {
        $payload = $request->getContent();
        if($payload instanceof Contact || is_array($payload))
        {
            $request->setContent($this->serializer->serialize($payload, 'json'));
            $request->addHeader('Content-Type: application/json');
        }
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
        return;
    }
}
