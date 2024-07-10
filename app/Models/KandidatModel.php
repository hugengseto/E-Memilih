<?php

namespace App\Models;

use CodeIgniter\Model;

class KandidatModel extends Model
{
    protected $table            = 'kandidat';
    protected $primaryKey       = 'kandidat_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nomor_urut', 'nama_kandidat', 'visi', 'misi', 'poster', 'media_pemilihan_id'];

    // Konfigurasi Timestamps
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime'; // Format tanggal dan waktu yang diinginkan, opsional

    // Field yang diisi secara otomatis
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataKandidatByMediaId($media_id)
    {
        $builder = $this->db->table($this->table)
            ->where('media_pemilihan_id', $media_id)
            ->orderBy('nomor_urut', 'ASC')
            ->get();

        if ($builder) {
            return $builder->getResultArray();
        } else {
            return null;
        }
    }

    public function getDataKandidatById($kandidat_id)
    {
        $builder = $this->db->table($this->table)
            ->where('kandidat_id', $kandidat_id)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }

    public function getDataKandidatByIdAndMediaId($kandidat_id, $media_id)
    {
        $builder = $this->db->table($this->table)
            ->where('kandidat_id', $kandidat_id, 'media_pemilihan_id', $media_id)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }
}
