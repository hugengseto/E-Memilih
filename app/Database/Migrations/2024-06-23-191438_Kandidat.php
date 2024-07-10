<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kandidat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kandidat_id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'nomor_urut' => [
                'type'          => 'INT',
                'contraint'     => 5,
                'unsigned'      => true,
            ],
            'nama_kandidat' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
                'null'          => false
            ],
            'visi' => [
                'type'          => 'TEXT',
                'null'          => false
            ],
            'misi' => [
                'type'          => 'TEXT',
                'null'          => false
            ],
            'poster' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
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

        $this->forge->addKey('kandidat_id', true);
        $this->forge->addForeignKey('media_pemilihan_id', 'media_pemilihan', 'media_id');
        $this->forge->createTable('kandidat', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('kandidat', true);
    }
}
