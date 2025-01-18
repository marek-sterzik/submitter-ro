<?php

namespace App\Utility;

class QueryParser
{
    public static function parse(string $query): array
    {
        if ($query === "") {
            return [];
        }
        return [
            [null, $query],
        ];
    }
}
