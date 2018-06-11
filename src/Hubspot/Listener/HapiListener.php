<?php

namespace Hubspot\Listener;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;

class HapiListener implements ListenerInterface
{
    /** @var string */
    protected $hapi;

    /**
     * HapiListener constructor.
     *
     * @param string $hapi
     */
    public function __construct(string $hapi)
    {
        $this->hapi = $hapi;
    }

    public function preSend(RequestInterface $request)
    {
        $request->setResource(sprintf(
                '%s%s%s',
                $request->getResource(),
                parse_url($request->getResource(), PHP_URL_QUERY) ? '&' : '?',
                sprintf('hapikey=%s', $this->hapi))
        );
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
        return;
    }
}
