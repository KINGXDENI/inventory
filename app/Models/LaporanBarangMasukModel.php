<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanBarangMasukModel extends Model
{
    protected $table            = 'laporan_barang_masuk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['barang_masuk_id', 'periode_awal', 'periode_akhir', 'total_barang_masuk'];
    protected $useTimestamps    = true;
}
