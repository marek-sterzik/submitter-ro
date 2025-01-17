<?php

namespace App\Utility;

use Exception;

class UrlJson
{
    public static function decode(string $data): mixed
    {
        try {
            $data = @json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            return $data;
        }
        return $data;
    }

    public static function encode(mixed $data): string
    {
        if (!is_string($data)) {
            $out = json_encode($data);
            if ($out !== false) {
                return $out;
            } else {
                throw new Exception('failed create json');
            }
        }
        try {
            @json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            return $data;
        }
        $out =  json_encode($data);
        if ($out !== false) {
            return $out;
        }
        throw new Exception('failed create json');
    }

}
