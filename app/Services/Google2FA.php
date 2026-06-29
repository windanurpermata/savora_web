<?php

namespace App\Services;

class Google2FA
{
    private static $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public static function generateSecretKey($length = 16): string
    {
        $secret = '';
        while (strlen($secret) < $length) {
            try {
                $secret .= self::$base32chars[random_int(0, 31)];
            } catch (\Exception $e) {
                $secret .= self::$base32chars[rand(0, 31)];
            }
        }
        return $secret;
    }

    public static function getQRCodeUrl($name, $secret, $issuer = 'Savora'): string
    {
        $data = rawurlencode("otpauth://totp/{$name}?secret={$secret}&issuer={$issuer}");
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={$data}";
    }

    public static function verifyKey($secret, $key, $window = 1): bool
    {
        if (empty($secret) || empty($key)) {
            return false;
        }

        $currentTimeSlice = floor(time() / 30);
        for ($i = -$window; $i <= $window; $i++) {
            $calculatedKey = self::getCode($secret, $currentTimeSlice + $i);
            if ($calculatedKey === $key) {
                return true;
            }
        }
        return false;
    }

    private static function getCode($secret, $timeSlice): string
    {
        $secretKey = self::base32Decode($secret);
        // Pack time slice to binary
        $time = chr(0).chr(0).chr(0).chr(0).pack('N', $timeSlice);
        // Hash it
        $hm = hash_hmac('sha1', $time, $secretKey, true);
        // Get offset
        $offset = ord(substr($hm, -1)) & 0x0F;
        // Get part of hash
        $hashpart = substr($hm, $offset, 4);
        // Unpack value
        $value = unpack('N', $hashpart);
        $value = $value[1];
        // Only 32 bits
        $value = $value & 0x7FFFFFFF;

        $modulo = pow(10, 6);
        return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
    }

    private static function base32Decode($base32): string
    {
        if (empty($base32)) {
            return '';
        }
        
        $base32 = strtoupper($base32);
        $base32 = str_replace('=', '', $base32);
        $allowedchars = self::$base32chars;
        
        $buf = 0;
        $bufSize = 0;
        $decoded = '';
        
        for ($i = 0; $i < strlen($base32); $i++) {
            $c = $base32[$i];
            $val = strpos($allowedchars, $c);
            if ($val === false) {
                continue;
            }
            $buf = ($buf << 5) | $val;
            $bufSize += 5;
            if ($bufSize >= 8) {
                $bufSize -= 8;
                $decoded .= chr(($buf >> $bufSize) & 0xFF);
            }
        }
        return $decoded;
    }
}
