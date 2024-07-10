<?php

namespace App\Models;

use CodeIgniter\Model;

class MediaModel extends Model
{
    protected $table            = 'media_pemilihan';
    protected $primaryKey       = 'media_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['judul_pemilihan', 'mulai_pemilihan', 'batas_pemilihan', 'kata_kunci', 'poster', 'slug'];

    // Konfigurasi Timestamps
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime'; // Format tanggal dan waktu yang diinginkan, opsional

    // Field yang diisi secara otomatis
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAllDataByNewData(): array
    {
        $builder = $this->db->table($this->table)
            ->orderBy('created_at', 'DESC')
            ->get();

        return $builder->getResultArray();
    }

    public function getMediaBySlug($slug): array|null
    {
        $builder = $this->db->table($this->table)
            ->where('slug', $slug)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }

    public function getMediaById($media_id): array|null
    {
        $builder = $this->db->table($this->table)
            ->where('media_id', $media_id)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }
}
