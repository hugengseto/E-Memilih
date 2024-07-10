<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HakPilih extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'hak_pilih_id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'peserta_id' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11
            ],
            'kandidat_id' => [
                'type'          => 'INT',
                'unsigned'      => true,
                'constraint'    => 11
            ],
            'media_pemilihan_id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
            ],
            'otp' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'status_konfirmasi' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
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

        $this->forge->addKey('hak_pilih_id', true);
        $this->forge->addForeignKey('kandidat_id', 'kandidat', 'kandidat_id');
        $this->forge->addForeignKey('peserta_id', 'peserta_pemilihan', 'peserta_id');
        $this->forge->addForeignKey('media_pemilihan_id', 'media_pemilihan', 'media_id');
        $this->forge->createTable('hak_pilih', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('hak_pilih', true);
    }
}
