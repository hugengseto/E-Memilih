<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MediaPemilihan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'media_id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'judul_pemilihan' => [
                'type'          => 'VARCHAR',
                'constraint'     => '255',
            ],
            'mulai_pemilihan' => [
                'type'  => 'DATETIME',
                'null'  => false
            ],
            'batas_pemilihan' => [
                'type'  => 'DATETIME',
                'null'  => false
            ],
            'kata_kunci' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
                'unique'        => true,
                'null'          => false
            ],
            'poster'  => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
                'null'          => true
            ],
            'slug'  => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
                'null'          => false
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

        $this->forge->addKey('media_id', true);
        $this->forge->addKey('judul_pemilihan', false, true);
        $this->forge->createTable('media_pemilihan', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('media_pemilihan', true);
    }
}
