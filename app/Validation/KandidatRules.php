<?php

namespace App\Validation;

use CodeIgniter\Database\BaseBuilder;

class KandidatRules
{
    public function unique_nomor_urut(string $str, string $fields, array $data): bool
    {
        // Parsing parameter fields
        list($fieldName, $mediaIdField, $mediaIdValue) = explode(',', $fields);

        $db = \Config\Database::connect();
        $builder = $db->table('kandidat');

        // Asumsikan 'id' adalah primary key dan 'nomor_urut' adalah field yang ingin diperiksa
        $builder->where($fieldName, $str);
        $builder->where($mediaIdField, $mediaIdValue);

        // Jika sedang dalam proses edit, periksa apakah nomor_urut milik kandidat lain
        if (isset($data['kandidat_id'])) {
            $builder->where('kandidat_id !=', $data['kandidat_id']);
        }

        // Dapatkan jumlah hasil yang sesuai dengan kriteria
        $count = $builder->countAllResults();

        // Jika tidak ada hasil yang ditemukan (berarti unik), atau sedang dalam proses edit
        return $count === 0 || isset($data['kandidat_id']);
    }

    public function unique_nomor_whatsapp(string $str, string $fields, array $data): bool
    {
        // Parsing parameter fields
        list($fieldName, $mediaIdField, $mediaIdValue) = explode(',', $fields);

        $db = \Config\Database::connect();
        $builder = $db->table('peserta_pemilihan');

        // Asumsikan 'id' adalah primary key dan 'nomor_urut' adalah field yang ingin diperiksa
        $builder->where($fieldName, $str);
        $builder->where($mediaIdField, $mediaIdValue);

        // Jika sedang dalam proses edit, periksa apakah nomor_urut milik kandidat lain
        if (isset($data['peserta_id'])) {
            $builder->where('peserta_id !=', $data['peserta_id']);
        }

        // Dapatkan jumlah hasil yang sesuai dengan kriteria
        $count = $builder->countAllResults();

        // Jika tidak ada hasil yang ditemukan (berarti unik), atau sedang dalam proses edit
        return $count === 0 || isset($data['peserta_id']);
    }
}
