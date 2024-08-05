<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kode_barang', 'nama_barang', 'merek', 'deskripsi', 'foto', 'stok', 'berat', 'satuan'];
    protected $useTimestamps    = true;
}
