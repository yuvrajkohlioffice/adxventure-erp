<?php

namespace App\Helpers;

class EncodingHelper
{
    /**
     * Recursively convert all string values to valid UTF-8.
     */
    public static function sanitizeUtf8($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitizeUtf8($value);
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->$key = self::sanitizeUtf8($value);
            }
        } elseif (is_string($data)) {
            $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        }

        return $data;
    }

}
