<?php

namespace App\Controllers;

use App\Models\BarangKeluarModel;
use App\Models\BarangModel;
use Dompdf\Dompdf;

class BarangKeluar extends BaseController
{

    public function index()
    {
        $barangKeluarModel = new BarangKeluarModel();
        $barangKeluar = $barangKeluarModel->getBarangKeluar();

        $kodeKeluar = array_unique(array_column($barangKeluarModel->findAll(), 'kode_keluar'));
        $data = [
            'title' => 'Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'kodeKeluar' => $kodeKeluar
        ];
        return view('barang_keluar/index', $data);
    }

    public function tambah()
    {
        $barangKeluarModel = new BarangKeluarModel();
        $barangModel = new BarangModel();
        $lastBarang = $barangKeluarModel->like('kode_keluar', 'BK-', 'after')->orderBy('kode_keluar', 'desc')->first();

        if ($lastBarang) {
            $lastNoUrut = (int) substr($lastBarang['kode_keluar'], 3); // Ambil nomor urut (integer)
            $noUrut = $lastNoUrut + 1;
        } else {
            $noUrut = 1; // Jika belum ada barang, mulai dari 1
        }

        $newKode = 'BK-' . sprintf('%03d', $noUrut);
        $data = [
            'title' => 'Tambah Barang Keluar',
            'barang' => $barangModel->findAll(),
            'kodeKeluar'=> $newKode,
            'validation' => \Config\Services::validation(),
        ];
        return view('barang_keluar/tambah', $data);
    }

    public function simpan()
    {
        $barangKeluarModel = new BarangKeluarModel();
        $barangModel = new BarangModel();

        $validationRules = [
            'barang_id.*' => 'required|integer',
            'jumlah_keluar.*' => 'required|greater_than[0]',
            'tanggal_keluar' => 'required|valid_date[Y-m-d\TH:i]',
            'keterangan.*' => 'permit_empty',
        ];

        $validationMessages = [
            'barang_id.*' => [
                'required' => 'Pilih barang yang keluar.',
                'integer' => 'ID barang harus berupa angka.',
            ],
            'jumlah_keluar.*' => [
                'required' => 'Jumlah keluar harus diisi.',
                'greater_than' => 'Jumlah keluar harus lebih dari 0.',
            ],
            'tanggal_keluar' => [
                'required' => 'Tanggal keluar harus diisi.',
                'valid_date' => 'Format tanggal dan waktu tidak valid.',
            ],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            $data = [
                'title' => 'Tambah Barang Keluar',
                'barang' => $barangModel->findAll(),
                'validation' => $this->validator,
            ];
            return view('barang_keluar/tambah', $data);
        }

        // Validate stock availability for each item
        $inputBarangIds = $this->request->getPost('barang_id');
        $inputJumlahKeluar = $this->request->getPost('jumlah_keluar');

        foreach ($inputBarangIds as $key => $barangId) {
            $barang = $barangModel->find($barangId);

            // Validasi stok barang
            if ($inputJumlahKeluar[$key] > $barang['stok']) {
                session()->setFlashdata('error', 'Stok tidak mencukupi untuk barang dengan ID: ' . $barangId);
                $data = [
                    'title' => 'Tambah Barang Keluar',
                    'barang' => $barangModel->findAll(),
                    'validation' => $this->validator,
                ];
                return view('barang_keluar/tambah', $data);
            }
        }

        // Insert data barang keluar
        foreach ($inputBarangIds as $key => $barangId) {
            $data = [
                'barang_id' => $barangId,
                'jumlah_keluar' => $inputJumlahKeluar[$key],
                'tanggal_keluar' => $this->request->getPost('tanggal_keluar'),
                'kode_keluar' => $this->request->getPost('kode_keluar'),
                'keterangan' => isset($inputKeterangan[$key]) ? $inputKeterangan[$key] : null,
            ];

            $barangKeluarModel->save($data);

            // Update stok barang
            $barang = $barangModel->find($barangId);
            $barang['stok'] -= $inputJumlahKeluar[$key];
            $barangModel->save($barang);
        }

        session()->setFlashdata('success', 'Barang keluar berhasil ditambahkan.');
        return redirect()->to('/barang-keluar');
    }


    public function edit($id)
    {
        $barangKeluarModel = new BarangKeluarModel();
        $barangModel = new BarangModel();

        $barangKeluar = $barangKeluarModel->find($id);
        if (!$barangKeluar) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'barang' => $barangModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('barang_keluar/edit', $data);
    }

    public function update($id)
    {
        $barangKeluarModel = new BarangKeluarModel();
        $barangModel = new BarangModel();
        $barangKeluar = $barangKeluarModel->find($id);
        if (!$barangKeluar) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validationRules = [
            'barang_id' => 'required|integer',
            'jumlah_keluar' => 'required|greater_than[0]',
            'tanggal_keluar' => 'required|valid_date[Y-m-d\TH:i]',
            'keterangan' => 'permit_empty',
        ];
        $validationMessages = [
            'barang_id' => [
                'required' => 'Pilih barang yang keluar.',
                'integer' => 'ID barang harus berupa angka.',
                'exists' => 'Barang tidak ditemukan.',
            ],
            'jumlah_keluar' => [
                'required' => 'Jumlah keluar harus diisi.',
                'greater_than' => 'Jumlah keluar harus lebih dari 0.',
            ],
            'tanggal_keluar' => [
                'required' => 'Tanggal keluar harus diisi.',
                'valid_date' => 'Format tanggal dan waktu tidak valid.',
            ],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            $data = [
                'title' => 'Edit Barang Keluar',
                'barangKeluar' => $barangKeluar, // Kirim kembali data barang keluar yang sedang diedit
                'barang' => $barangModel->findAll(),
                'validation' => $this->validator,
            ];
            return view('barang_keluar/edit', $data);
        }

        $barangId = $this->request->getPost('barang_id');
        $barang = $barangModel->find($barangId);

        // Validasi stok barang
        $jumlahKeluar = $this->request->getPost('jumlah_keluar');
        if ($jumlahKeluar > $barang['stok'] + $barangKeluar['jumlah_keluar']) { // Tambahkan jumlah keluar lama saat validasi
            session()->setFlashdata('error', 'Stok tidak mencukupi.');
            $data = [
                'title' => 'Edit Barang Keluar',
                'barangKeluar' => $barangKeluar, // Kirim kembali data barang keluar yang sedang diedit
                'barang' => $barangModel->findAll(),
                'validation' => $this->validator,
            ];
            return view('barang_keluar/edit', $data);
        }

        // Hitung selisih jumlah keluar
        $selisihJumlah = $jumlahKeluar - $barangKeluar['jumlah_keluar'];

        $data = [
            'barang_id' => $barangId,
            'jumlah_keluar' => $jumlahKeluar,
            'tanggal_keluar' => $this->request->getPost('tanggal_keluar'),
            'kode_keluar' => $this->request->getPost('kode_keluar'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $barangKeluarModel->update($id, $data);

        // Update stok barang
        $barang['stok'] -= $selisihJumlah;
        $barangModel->save($barang);

        session()->setFlashdata('success', 'Barang keluar berhasil diperbarui.');
        return redirect()->to('/barang-keluar');
    }

    public function hapus($id)
    {
        $barangKeluarModel = new BarangKeluarModel();
        $barangModel = new BarangModel();
        $barangKeluar = $barangKeluarModel->find($id);
        if (!$barangKeluar) {
            session()->setFlashdata('error', 'Data barang keluar tidak ditemukan.');
            $data = [
                'title' => 'Barang Keluar',
                'barangKeluar' => $barangKeluarModel->getBarangKeluar(),
            ];
            return view('barang_keluar/index', $data);
        }

        // Update stok barang sebelum menghapus barang keluar
        $barang = $barangModel->find($barangKeluar['barang_id']);
        if ($barang) {
            $barang['stok'] += $barangKeluar['jumlah_keluar']; // Kembalikan stok yang keluar
            $barangModel->save($barang);
        }

        $barangKeluarModel->delete($id);

        session()->setFlashdata('success', 'Data barang keluar berhasil dihapus.');
        return redirect()->to('/barang-keluar');
    }
    public function filter()
    {
        $kode_keluar = $this->request->getGet('kode_keluar');
        $periode_awal = $this->request->getGet('periode_awal');
        $periode_akhir = $this->request->getGet('periode_akhir');

        $barangKeluarModel = new BarangKeluarModel();
        $barangKeluar = $barangKeluarModel->filterBarangKeluar($kode_keluar, $periode_awal, $periode_akhir);

        $kodeKeluar = array_unique(array_column($barangKeluarModel->findAll(), 'kode_keluar'));

        $data = [
            'title' => 'Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'kodeKeluar' => $kodeKeluar,
            'hasResults' => !empty($barangKeluar),
            'kode_keluar' => $kode_keluar,
            'periode_awal' => $periode_awal,
            'periode_akhir' => $periode_akhir,
        ];

        return view('barang_keluar/index', $data);
    }

    public function reset()
    {
        $barangKeluarModel = new BarangKeluarModel();
        $barangKeluar = $barangKeluarModel->getBarangKeluar();

        $kodeKeluar = array_unique(array_column($barangKeluarModel->findAll(), 'kode_keluar'));
        $data = [
            'title' => 'Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'kodeKeluar' => $kodeKeluar,
           
        ];

        return view('barang_keluar/index', $data);
    }

    public function print()
    {
        $kode_keluar = $this->request->getGet('kode_keluar');
        $periode_awal = $this->request->getGet('periode_awal');
        $periode_akhir = $this->request->getGet('periode_akhir');
        
        $barangKeluarModel = new BarangKeluarModel();
        $barangKeluar = $barangKeluarModel->filterBarangKeluar($kode_keluar, $periode_awal, $periode_akhir);
        
        $data = [
            'title' => 'Laporan Barang Keluar',
            'barangKeluar' => $barangKeluar,
        ];


        $dompdf = new \Dompdf\Dompdf();
        $dompdf = new Dompdf(['isHtml5ParserEnabled' => true]);
        $dompdf->loadHtml(view('barang_keluar/print', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $stream = TRUE;
        if ($stream) {
            $dompdf->stream("Nota_Barang_Keluar" . ".pdf", array("Attachment" => 0));
            exit();
        } else {
            return $dompdf->output();
        }
    }




}
