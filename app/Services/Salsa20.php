<?php

namespace App\Services;

class Salsa20
{
    private static function quarterRound(&$a, &$b, &$c, &$d)
    {
        $b ^= self::rotate(($a + $d) & 0xFFFFFFFF, 7);
        $c ^= self::rotate(($b + $a) & 0xFFFFFFFF, 9);
        $d ^= self::rotate(($c + $b) & 0xFFFFFFFF, 13);
        $a ^= self::rotate(($d + $c) & 0xFFFFFFFF, 18);
    }

    private static function rotate($v, $n)
    {
        $v = $v & 0xFFFFFFFF;
        return ((($v << $n) | ($v >> (32 - $n))) & 0xFFFFFFFF);
    }

    public static function salsa20Block($key, $nonce, $blockIndex)
    {
        // key: 32 bytes, nonce: 8 bytes, blockIndex: 8-byte counter
        $constants = [0x61786520, 0x3320646e, 0x79622d32, 0x6b206574]; // "expand 32-byte k"

        $k = unpack('V8', $key);
        $n = unpack('V2', $nonce);
        // counter is represented as two 32-bit words
        $c = [$blockIndex & 0xFFFFFFFF, ($blockIndex >> 32) & 0xFFFFFFFF];

        $x = [
            $constants[0], $k[1],         $k[2],         $k[3],
            $k[4],         $constants[1], $n[1],         $n[2],
            $c[0],         $c[1],         $constants[2], $k[5],
            $k[6],         $k[7],         $k[8],         $constants[3]
        ];

        $state = $x;

        for ($i = 0; $i < 20; $i += 2) {
            // Odd rounds
            self::quarterRound($state[0], $state[4], $state[8], $state[12]);
            self::quarterRound($state[5], $state[9], $state[13], $state[1]);
            self::quarterRound($state[10], $state[14], $state[2], $state[6]);
            self::quarterRound($state[15], $state[3], $state[7], $state[11]);

            // Even rounds
            self::quarterRound($state[0], $state[1], $state[2], $state[3]);
            self::quarterRound($state[5], $state[6], $state[7], $state[4]);
            self::quarterRound($state[10], $state[11], $state[8], $state[9]);
            self::quarterRound($state[15], $state[12], $state[13], $state[14]);
        }

        for ($i = 0; $i < 16; $i++) {
            $state[$i] = ($state[$i] + $x[$i]) & 0xFFFFFFFF;
        }

        return pack('V16', ...$state);
    }

    public static function encrypt($plaintext, $key, $nonce = null)
    {
        if ($plaintext === null || $plaintext === '') {
            return $plaintext;
        }

        // Hash the key to exactly 32 bytes
        $key = hash('sha256', $key, true);

        if ($nonce === null) {
            $nonce = random_bytes(8);
        }

        $ciphertext = '';
        $len = strlen($plaintext);
        $blocks = ceil($len / 64);

        for ($i = 0; $i < $blocks; $i++) {
            $keystream = self::salsa20Block($key, $nonce, $i);
            $chunk = substr($plaintext, $i * 64, 64);
            $ciphertext .= $chunk ^ substr($keystream, 0, strlen($chunk));
        }

        return base64_encode($nonce . $ciphertext);
    }

    public static function decrypt($payload, $key)
    {
        if ($payload === null || $payload === '') {
            return $payload;
        }

        $data = base64_decode($payload);
        if (strlen($data) < 8) {
            return null;
        }
        
        $nonce = substr($data, 0, 8);
        $ciphertext = substr($data, 8);
        $key = hash('sha256', $key, true);

        $plaintext = '';
        $len = strlen($ciphertext);
        $blocks = ceil($len / 64);

        for ($i = 0; $i < $blocks; $i++) {
            $keystream = self::salsa20Block($key, $nonce, $i);
            $chunk = substr($ciphertext, $i * 64, 64);
            $plaintext .= $chunk ^ substr($keystream, 0, strlen($chunk));
        }

        return $plaintext;
    }
}
