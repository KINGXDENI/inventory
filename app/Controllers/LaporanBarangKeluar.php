<?php

namespace App\Controllers;

use App\Models\LaporanBarangKeluarModel;
use App\Models\BarangKeluarModel;

class LaporanBarangKeluar extends BaseController
{

    public function index()
    {
        $laporanBarangKeluarModel = new LaporanBarangKeluarModel();
        $data = [
            'title' => 'Laporan Barang Keluar',
            'laporanBarangKeluar' => $laporanBarangKeluarModel->findAll(),
        ];
        return view('laporan_barang_keluar/index', $data);
    }

    public function generate()
    {
        $laporanBarangKeluarModel = new LaporanBarangKeluarModel();
        $barangKeluarModel = new BarangKeluarModel();
        $periodeAwal = $this->request->getPost('periode_awal');
        $periodeAkhir = $this->request->getPost('periode_akhir');

        $barangKeluar = $barangKeluarModel
            ->where('tanggal_keluar >=', $periodeAwal)
            ->where('tanggal_keluar <=', $periodeAkhir)
            ->findAll();

        $totalBarangKeluar = 0;
        foreach ($barangKeluar as $item) {
            $totalBarangKeluar += $item['jumlah_keluar'];
        }

        $dataLaporan = [
            'periode_awal' => $periodeAwal,
            'periode_akhir' => $periodeAkhir,
            'total_barang_keluar' => $totalBarangKeluar,
        ];

        $laporanBarangKeluarModel->insert($dataLaporan);

        session()->setFlashdata('success', 'Laporan barang keluar berhasil dibuat.');
        return redirect()->to('/laporan-barang-keluar');
    }
}
