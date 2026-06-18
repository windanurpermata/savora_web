<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail('Verifikasi reCAPTCHA wajib diisi.');
            return;
        }

        // Untuk testing / development lokal tanpa keys, kita bisa skip validasi jika tidak diset di .env
        if (app()->runningUnitTests()) {
            return;
        }

        $secretKey = config('services.recaptcha.secret_key');
        if (empty($secretKey)) {
            return;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $value,
        ]);

        if (!$response->successful() || !$response->json('success')) {
            $fail('Verifikasi reCAPTCHA gagal, silakan coba lagi.');
        }
    }
}
