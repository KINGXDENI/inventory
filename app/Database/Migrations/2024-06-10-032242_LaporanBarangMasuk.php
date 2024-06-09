<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LaporanBarangMasuk extends Migration
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
            'barang_masuk_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'periode_awal' => [
                'type' => 'DATE',
            ],
            'periode_akhir' => [
                'type' => 'DATE',
            ],
            'total_barang_masuk' => [
                'type' => 'INT',
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
        $this->forge->addForeignKey('barang_masuk_id', 'barang_masuk', 'id');
        $this->forge->createTable('laporan_barang_masuk');
    }

    public function down()
    {
        $this->forge->dropTable('laporan_barang_masuk');
    }
}
