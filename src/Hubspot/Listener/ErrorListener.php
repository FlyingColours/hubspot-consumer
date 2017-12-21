<?php

namespace Hubspot\Listener;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;
use Buzz\Message\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
            switch ($response->getStatusCode())
            {
                case 401: throw new UnauthorizedHttpException('hapi', $response->getReasonPhrase());
                case 403: throw new AccessDeniedHttpException($response->getReasonPhrase());
                case 404: throw new NotFoundHttpException($response->getReasonPhrase());
                default: throw new HttpException($response->getStatusCode(), $response->getReasonPhrase());
            }
        }
    }
}
