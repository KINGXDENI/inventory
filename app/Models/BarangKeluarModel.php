<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangKeluarModel extends Model
{
    protected $table            = 'barang_keluar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields = ['barang_id', 'jumlah_keluar', 'tanggal_keluar', 'kode_keluar', 'keterangan'];
    protected $useTimestamps    = true;

    public function getBarangKeluar()
    {
        return $this->select('barang_keluar.*, barang.nama_barang')
            ->join('barang', 'barang.id = barang_keluar.barang_id')
            ->findAll();
    }
}
