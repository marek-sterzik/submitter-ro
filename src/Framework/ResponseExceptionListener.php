<?php

namespace App\Framework;

use App\Exception\ResponseException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ResponseExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ResponseException) {
            $event->setResponse($exception->getResponse());
        }
    }
}
