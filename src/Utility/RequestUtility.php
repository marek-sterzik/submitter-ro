<?php

namespace App\Utility;

use Symfony\Component\HttpFoundation\Request;

class RequestUtility
{
    public static function modifyUri(Request $request, array $modifiedQuery): string
    {
        $query = $request->query->all();
        foreach ($modifiedQuery as $key => $value) {
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
        $uri = $request->getBasePath() . $request->getPathInfo() . $queryString;
        return $uri;
    }
}
