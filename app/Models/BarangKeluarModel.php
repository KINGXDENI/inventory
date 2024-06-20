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

    public function getLaporanBarangKeluar($periodeAwal = null, $periodeAkhir = null, $barangId = null)
    {
        $builder = $this->select('barang_keluar.*, barang.nama_barang')
        ->join('barang', 'barang.id = barang_keluar.barang_id');

        if ($periodeAwal) {
            $builder->where('DATE(barang_keluar.tanggal_keluar) >=', $periodeAwal);
        }

        if ($periodeAkhir) {
            $builder->where('DATE(barang_keluar.tanggal_keluar) <=', $periodeAkhir);
        }

        if (!empty($barangId)) { // Filter hanya jika $barangId tidak kosong
            $builder->where('barang_keluar.barang_id', $barangId);
        }

        return $builder->findAll();
    }
}
