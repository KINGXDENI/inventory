<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BarangMasuk extends Migration
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
            'barang_id' => [ // Relasi ke tabel barang
                'type' => 'INT',
                'unsigned' => true,
            ],
            'jumlah_masuk' => [
                'type' => 'INT',
            ],
            'tanggal_masuk' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('barang_id', 'barang', 'id'); // Foreign key ke tabel barang
        $this->forge->createTable('barang_masuk');
    }

    public function down()
    {
        $this->forge->dropTable('barang_masuk');
    }
}