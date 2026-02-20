<?php

use Illuminate\Support\Facades\Vite;

/**
 * Safe Vite image helper — works on both dev (Vite server) and shared hosting (built manifest).
 * Falls back to asset() from public/images/ if Vite manifest is missing.
 *
 * Usage in Blade: {{ vite_image('pgmf-logo.jpg') }}
 *                 {{ vite_image('banks/bay.svg') }}
 */
if (!function_exists('vite_image')) {
    function vite_image(string $path): string
    {
        try {
            return Vite::asset('resources/images/' . $path);
        } catch (\Throwable $e) {
            return asset('images/' . $path);
        }
    }
}
