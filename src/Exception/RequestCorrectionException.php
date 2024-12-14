<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Utility\RequestUtility;

class RequestCorrectionException extends Exception
{
    public function __construct(private Request $request, private array $correctedQuery)
    {
        parent::__construct("RequestCorrectionException");
    }

    public function getRedirectResponse(): Response
    {
        $uri = RequestUtility::modifyUri($this->request, $this->correctedQuery);
        return new RedirectResponse($uri);
    }
}
