<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Exception;

class RequestCorrectionException extends Exception
{
    public function __construct(private Request $request, private array $correctedQuery)
    {
        parent::__construct("RequestCorrectionException");
    }

    public function getRedirectResponse(): Response
    {
        $query = $this->request->query->all();
        foreach ($this->correctedQuery as $key => $value) {
            if ($value === null) {
                unset($query[$key]);
            } else {
                $query[$key] = $value;
            }
        }
        $queryString = http_build_query($query);
        if ($queryString !== "") {
            $queryString = "?" . $queryString;
        }
        $uri = $this->request->getBasePath() . $this->request->getPathInfo() . $queryString;
        return new RedirectResponse($uri);
    }
}
