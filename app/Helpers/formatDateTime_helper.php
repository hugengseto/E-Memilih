<?php

if (!function_exists('format_datetime')) {
    function format_datetime($datetime, $format = 'd-m-Y H:i:s')
    {
        // Ubah string datetime menjadi objek DateTime
        $dateTime = new DateTime($datetime);

        // Formatkan tanggal dan waktu sesuai dengan format yang diinginkan
        return $dateTime->format($format);
    }

    function format_tanggal_lahir($datetime, $format = 'd-m-Y')
    {
        // Ubah string datetime menjadi objek DateTime
        $dateTime = new DateTime($datetime);

        // Formatkan tanggal dan waktu sesuai dengan format yang diinginkan
        return $dateTime->format($format);
    }

    function format_tanggal_default_plugin($datetime, $format = 'm/d/Y')
    {
        // Ubah string datetime menjadi objek DateTime
        $dateTime = new DateTime($datetime);

        // Formatkan tanggal dan waktu sesuai dengan format yang diinginkan
        return $dateTime->format($format);
    }
}
