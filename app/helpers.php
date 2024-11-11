<?php

use Illuminate\Support\Str;

if (!function_exists('limitHtml')) {
    function limitHtml($text, $limit = 100, $end = '...')
    {
        $text = strip_tags($text, '<br><p>'); // Izinkan tag HTML tertentu
        $text = Str::limit($text, $limit, $end); // Batasi panjang teks

        // Pastikan jika tag terbuka, maka ditutup dengan benar
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">' . $text); // Tambahkan header XML agar DOMDocument bisa memproses HTML dengan benar
        libxml_clear_errors();

        return $dom->saveHTML();
    }
}

