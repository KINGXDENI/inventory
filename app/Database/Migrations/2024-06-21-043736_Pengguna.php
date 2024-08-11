<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pengguna extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 75,
            ],
            'jabatan' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'manager'],
            ],
            'profile_pic' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true, // Foto profil bisa kosong
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique' => true, // Email harus unik
            ],
            'no_telfon' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true, // Nomor telepon bisa kosong
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true, // Bisa null untuk saat pembuatan
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true, // Bisa null untuk saat pembuatan
            ],
        ]);
        $this->forge->addKey('id', true); // Primary key
        $this->forge->createTable('pengguna');
    }

    public function down()
    {
        $this->forge->dropTable('pengguna');
    }
}
