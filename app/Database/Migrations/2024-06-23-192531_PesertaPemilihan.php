<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PesertaPemilihan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'peserta_id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'nama_lengkap' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
                'null'          => false
            ],
            'jenis_kelamin' => [
                'type'          => 'ENUM',
                'constraint'    => ['Laki-Laki', 'Perempuan', '-'],
                'default'          => '-',
            ],
            'nomor_whatsapp' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
                'null'          => false
            ],
            'tanggal_lahir' => [
                'type'          => 'DATE',
            ],
            'media_pemilihan_id' => [
                'type'              => 'INT',
                'constraint'        => 11,
                'unsigned'      => true,
                'null'              => false
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('peserta_id', true);
        $this->forge->addForeignKey('media_pemilihan_id', 'media_pemilihan', 'media_id');
        $this->forge->createTable('peserta_pemilihan', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('peserta_pemilihan', true);
    }
}
