<?php

namespace App\Helpers;

class Formatter
{
    public function rawPhoneNumber(
        string $phoneNumber = null
    ) {
        if ($phoneNumber) {
            $rawPhone = preg_replace('~\D~', '', $phoneNumber);
            if (!$rawPhone)
                return false;
            $rawPhone = ($rawPhone[0] == 1) ? substr($rawPhone, 1) : $rawPhone;
            return $rawPhone;
        }
        return false;
    }
}