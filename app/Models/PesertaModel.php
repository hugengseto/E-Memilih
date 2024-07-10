<?php

namespace App\Models;

use CodeIgniter\Model;

class PesertaModel extends Model
{
    protected $table            = 'peserta_pemilihan';
    protected $primaryKey       = 'peserta_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_lengkap', 'jenis_kelamin', 'nomor_whatsapp', 'tanggal_lahir', 'media_pemilihan_id'];

    // Konfigurasi Timestamps
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime'; // Format tanggal dan waktu yang diinginkan, opsional

    // Field yang diisi secara otomatis
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataPesertaByMediaId($media_id)
    {
        $builder = $this->db->table($this->table)
            ->join('media_pemilihan', 'media_pemilihan.media_id = peserta_pemilihan.media_pemilihan_id')
            ->where('media_pemilihan_id', $media_id)
            ->orderBy('nama_lengkap', 'ASC')
            ->get();

        if ($builder) {
            return $builder->getResultArray();
        } else {
            return null;
        }
    }

    public function getDataPesertaById($peserta_id)
    {
        $builder = $this->db->table($this->table)
            ->where('peserta_id', $peserta_id)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }

    public function getDataPesertaByIdAndMediaId($peserta_id, $media_id)
    {
        $builder = $this->db->table($this->table)
            ->where('peserta_id', $peserta_id, 'media_pemilihan_id', $media_id)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }

    // query untuk mengambil data peserta berdasarkan nowhatsapp dan media pemilihannya
    public function getDataPesertaByNoWhatsappAndMediaId($nomor_whatsapp, $media_id)
    {
        $builder = $this->db->table($this->table)
            ->where('nomor_whatsapp', $nomor_whatsapp)
            ->where('media_pemilihan_id', $media_id)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }
}
