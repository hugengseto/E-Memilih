<?php

namespace App\Models;

use CodeIgniter\Model;

class HakPilihModel extends Model
{
    protected $table            = 'hak_pilih';
    protected $primaryKey       = 'hak_pilih_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['peserta_id', 'kandidat_id', 'media_pemilihan_id', 'otp', 'status_konfirmasi'];

    // Konfigurasi Timestamps
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime'; // Format tanggal dan waktu yang diinginkan, opsional

    // Field yang diisi secara otomatis
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // cek sudah memilih
    public function getDataByStatusKonfirmasi($peserta_id, $statusKonfrimasi, $media_id)
    {
        $builder = $this->db->table($this->table)
            ->where('peserta_id', $peserta_id)
            ->where('media_pemilihan_id', $media_id)
            ->where('status_konfirmasi', $statusKonfrimasi)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }

    // ambil data sesuai id hak pilih
    public function getHakPilihById($hakPilihId)
    {
        $builder = $this->db->table($this->table)
            ->select("{$this->table}.*, peserta_pemilihan.peserta_id, peserta_pemilihan.nomor_whatsapp, peserta_pemilihan.nama_lengkap, kandidat.nama_kandidat,kandidat.nomor_urut,kandidat.visi, kandidat.misi, kandidat.poster as poster_kandidat, media_pemilihan.media_id, media_pemilihan.slug, media_pemilihan.poster as poster_media_pemilihan")
            ->join('kandidat', "kandidat.kandidat_id = {$this->table}.kandidat_id")
            ->join('peserta_pemilihan', "{$this->table}.peserta_id = peserta_pemilihan.peserta_id")
            ->join('media_pemilihan', "media_pemilihan.media_id = kandidat.media_pemilihan_id")
            ->where('hak_pilih_id', $hakPilihId)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }

    // ambil data peserta berdasarkan peserta id dan media id
    public function getDataByPesertaIdNMediaId($peserta_id, $media_id)
    {
        $builder = $this->db->table($this->table)
            ->where('peserta_id', $peserta_id)
            ->where('media_pemilihan_id', $media_id)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }

    // cek data nomor_hp, kandidat, media pemilihan
    public function getDataByPesertaIdNKandidat($peserta_id, $kandidat_id)
    {
        $builder = $this->db->table($this->table)
            ->select("{$this->table}.*, peserta_pemilihan.peserta_id, peserta_pemilihan.nomor_whatsapp, peserta_pemilihan.nama_lengkap, kandidat.nama_kandidat,kandidat.nomor_urut,kandidat.visi, kandidat.misi, kandidat.poster, media_pemilihan.media_id, media_pemilihan.slug")
            ->join('kandidat', "kandidat.kandidat_id = {$this->table}.kandidat_id")
            ->join('peserta_pemilihan', "{$this->table}.peserta_id = peserta_pemilihan.peserta_id")
            ->join('media_pemilihan', "media_pemilihan.media_id = kandidat.media_pemilihan_id")
            ->where("{$this->table}.peserta_id", $peserta_id)
            ->where("{$this->table}.kandidat_id", $kandidat_id)
            ->get();

        if ($builder) {
            return $builder->getRowArray();
        } else {
            return null;
        }
    }

    // cek kode otp 
    public function getOtpFromRandomOtp($otp)
    {
        $builder = $this->db->table($this->table)
            ->where('otp', $otp)
            ->get();

        if ($builder->getRowArray()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // ambil data berdasarkan id peserta
    public function getDataByPesertaId($peserta_id)
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

    // ambil data berdasarkan media id
    public function getDataByMediaId($media_id)
    {
        $builder = $this->db->table($this->table)
            ->where('media_pemilihan_id', $media_id)
            ->where('status_konfirmasi', 'Sudah')
            ->get();

        if ($builder) {
            return $builder->getResultArray();
        } else {
            return null;
        }
    }

    // ambil data berdasarkan kandidat id
    public function getDataHakPilihByKandidatId($kandidat_id)
    {
        $builder = $this->db->table($this->table)
            ->where('kandidat_id', $kandidat_id)
            ->where('status_konfirmasi', 'Sudah')
            ->get();

        if ($builder) {
            return $builder->getResultArray();
        } else {
            return null;
        }
    }
}
