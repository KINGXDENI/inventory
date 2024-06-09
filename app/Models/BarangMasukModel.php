<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model
{
    protected $table            = 'barang_masuk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['barang_id', 'jumlah_masuk', 'tanggal_masuk'];
    protected $useTimestamps    = true;

    public function getBarangMasuk()
    {
        return $this->select('barang_masuk.*, barang.nama_barang')
        ->join('barang', 'barang.id = barang_masuk.barang_id')
        ->findAll();
    }
}

