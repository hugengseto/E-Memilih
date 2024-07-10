<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['full_name', 'email', 'password'];

    // Konfigurasi Timestamps
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime'; // Format tanggal dan waktu yang diinginkan, opsional

    // Field yang diisi secara otomatis
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataByUsername($username)
    {
        $builder = $this->db->table($this->table)
            ->where('full_name', $username)
            ->get();

        if ($builder) {
            return $builder->getFirstRow();
        } else {
            return null;
        }
    }
}
