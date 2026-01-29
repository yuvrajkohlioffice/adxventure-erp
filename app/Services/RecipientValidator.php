<?php

namespace App\Services;

class RecipientValidator
{
    /**
     * Validate email address
     */
    public static function email(?string $email): bool
    {
        if (!$email) {
            return false;
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate Indian mobile number (10 digit, starts with 6-9)
     */
    public static function phone(?string $phone): bool
    {
        if (!$phone) {
            return false;
        }

        // Remove spaces, +91, dashes
        $phone = preg_replace('/\s+|-/','', $phone);
        $phone = preg_replace('/^\+91/','', $phone);

        return preg_match('/^[6-9]\d{9}$/', $phone) === 1;
    }

    /**
     * Normalize phone number (store clean value)
     */
    public static function normalizePhone(?string $phone): ?string
    {
        if (!$phone) return null;

        $phone = preg_replace('/\D/','', $phone);

        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }

        return $phone;
    }
}
