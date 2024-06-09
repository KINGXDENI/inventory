<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LaporanBarangKeluar extends Migration
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
            'barang_keluar_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'periode_awal' => [
                'type' => 'DATE',
            ],
            'periode_akhir' => [
                'type' => 'DATE',
            ],
            'total_barang_keluar' => [
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
        $this->forge->addForeignKey('barang_keluar_id', 'barang_keluar', 'id');
        $this->forge->createTable('laporan_barang_keluar');
    }

    public function down()
    {
        $this->forge->dropTable('laporan_barang_keluar');
    }
}
