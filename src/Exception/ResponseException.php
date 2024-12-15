<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ResponseException extends Exception
{
    public function __construct(private Response $response)
    {
        parent::__construct("ResponseException");
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
