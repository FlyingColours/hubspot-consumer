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
        parse_str(parse_url($request->getResource(), PHP_URL_QUERY), $query);

        $query['hapikey'] = $this->hapi;

        $request->setResource(
            sprintf('%s?%s',
                preg_replace('/\?.*/', '', $request->getResource()),
                http_build_query($query)
            )
        );
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
        return;
    }
}
