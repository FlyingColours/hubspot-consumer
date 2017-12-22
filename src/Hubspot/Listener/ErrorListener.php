<?php

namespace Hubspot\Listener;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;
use Buzz\Message\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorListener implements ListenerInterface
{
    public function preSend(RequestInterface $request)
    {
        return;
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
        /** @var Response $response */
        if( ! $response->isSuccessful())
        {
            throw new HttpException($response->getStatusCode(), $response->getReasonPhrase());
        }
    }
}
