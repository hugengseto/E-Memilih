<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MediaModel;
use App\Models\PesertaModel;
use CodeIgniter\HTTP\ResponseInterface;

class DownloadController extends BaseController
{
    public function file_csv($filename)
    {
        // Path ke direktori public/csv
        $path = FCPATH . 'csv/' . $filename;

        // Cek apakah file ada
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        // Gunakan helper download
        return $this->response->download($path, null)->setFileName($filename);
    }

    public function export_csv($slug)
    {
        // ambil data media pemilihan
        $modelMediaPemilihan = new MediaModel();
        $media = $modelMediaPemilihan->getMediaBySlug($slug);
        // ambil data peserta berdasarkan media id
        $modelPeserta = new PesertaModel();
        $peserta = $modelPeserta->getDataPesertaByMediaId($media['media_id']);


        // Tentukan header file CSV
        $csvHeader = ['No', 'Nama Lengkap', 'Jenis Kelamin', 'Nomor Whatsapp', 'Tanggal Lahir'];

        // Tentukan nama file
        $fileName = 'data-peserta-' . $media['slug'] . '-' . date('YmdHis') . '.csv';

        // Atur header untuk meminta diunduh
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        //Buka aliran output PHP untuk menulis
        $file = fopen('php://output', 'w');

        // Tulis pembaca CSV
        fputcsv($file, $csvHeader);

        // Tulis baris data
        $no = 1;
        foreach ($peserta as $row) {
            $jenis_kelamin = '';
            if ($row['jenis_kelamin'] == 'Laki-Laki') {
                $jenis_kelamin = "L";
            } else {
                $jenis_kelamin = "P";
            }
            fputcsv($file, [
                $no++,
                $row['nama_lengkap'],
                $jenis_kelamin,
                $row['nomor_whatsapp'],
                $row['tanggal_lahir'],
            ]);
        };

        // Close the output stream
        fclose($file);
        exit();
    }
}
