<?php

if (!function_exists('createSlug')) {
    function createSlug($string)
    {
        // Ubah semua karakter menjadi huruf kecil
        $string = strtolower($string);

        // Ganti karakter non-alfanumerik dengan tanda hubung
        $string = preg_replace('/[^a-z0-9]+/i', '-', $string);

        // Hapus tanda hubung di awal dan akhir string
        $string = trim($string, '-');

        return $string;
    }
}
