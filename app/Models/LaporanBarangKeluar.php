<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanBarangKeluarModel extends Model
{
    protected $table            = 'laporan_barang_keluar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['barang_keluar_id', 'periode_awal', 'periode_akhir', 'total_barang_keluar'];
    protected $useTimestamps    = true;
}
