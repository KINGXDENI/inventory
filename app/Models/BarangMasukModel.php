<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model
{
    protected $table            = 'barang_masuk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields = ['barang_id', 'jumlah_masuk', 'tanggal_masuk', 'kode_masuk', 'keterangan'];
    protected $useTimestamps    = true;

    public function getBarangMasuk()
    {
        return $this->select('barang_masuk.*, barang.nama_barang')
        ->join('barang', 'barang.id = barang_masuk.barang_id')
        ->findAll();
    }

    public function getLaporanBarangMasuk($periodeAwal = null, $periodeAkhir = null, $barangId = null)
    {
        $builder = $this->select('barang_masuk.*, barang.nama_barang')
            ->join('barang', 'barang.id = barang_masuk.barang_id');

        if ($periodeAwal) {
            $builder->where('DATE(barang_masuk.tanggal_masuk) >=', $periodeAwal);
        }

        if ($periodeAkhir) {
            $builder->where('DATE(barang_masuk.tanggal_masuk) <=', $periodeAkhir);
        }

        if (!empty($barangId)) { // Filter hanya jika $barangId tidak kosong
            $builder->where('barang_masuk.barang_id', $barangId);
        }

        return $builder->findAll();
    }


}

