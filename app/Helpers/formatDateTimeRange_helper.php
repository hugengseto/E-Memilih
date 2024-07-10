<?php

if (!function_exists('format_datetime_range')) {
    function format_datetime_range($start_datetime, $end_datetime): string
    {
        // Ubah string datetime menjadi objek DateTime
        $start = new DateTime($start_datetime);
        $end = new DateTime($end_datetime);

        // Format tanggal dan waktu sesuai dengan format yang diinginkan
        $start_formatted = $start->format('m/d/Y h:i A');
        $end_formatted = $end->format('m/d/Y h:i A');

        // Mengembalikan format gabungan
        return $start_formatted . ' - ' . $end_formatted;
    }
}
