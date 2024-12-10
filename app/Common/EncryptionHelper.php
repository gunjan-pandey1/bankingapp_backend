<?php

namespace App\Common;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class EncryptionHelper
{
    public function encrypt($value)
    {
        return Crypt::encryptString($value);
    }

    public function decrypt($value)
    {
        return Crypt::decryptString($value);
    }

    public function hash($value)
    {
        return Hash::make($value);
    }

    public function checkHash($value, $hashedValue)
    {
        return Hash::check($value, $hashedValue);
    }

    public function generateToken()
    {
        return bin2hex(random_bytes(30)); // Generate a random token
    }

    public function tokenGenerationWallet($data, $secret, $algo = 'SHA512')
    {
        return hash_hmac($algo, json_encode(array_map('strval', $data)), $secret);
    }
}
