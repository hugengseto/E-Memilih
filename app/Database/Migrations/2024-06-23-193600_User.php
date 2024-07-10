<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'full_name' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
            ],
            'email' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
            ],
            'password' => [
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

        $this->forge->addKey('user_id', true);
        $this->forge->addKey('email', false, true);
        $this->forge->createTable('user', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('user', true);
    }
}
