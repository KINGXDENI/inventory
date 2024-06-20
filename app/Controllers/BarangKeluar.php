<?php

namespace App\Controllers;

use App\Models\BarangKeluarModel;
use App\Models\BarangModel;

class BarangKeluar extends BaseController
{

    public function index()
    {
        $barangKeluarModel = new BarangKeluarModel();
        $data = [
            'title' => 'Barang Keluar',
            'barangKeluar' => $barangKeluarModel->getBarangKeluar(),
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
            'barang_id' => 'required|integer',
            'jumlah_keluar' => 'required|greater_than[0]',
            'tanggal_keluar' => 'required|valid_date[Y-m-d\TH:i]',
            'keterangan' => 'permit_empty',
        ];
        $validationMessages = [
            'barang_id' => [
                'required' => 'Pilih barang yang keluar.',
                'integer' => 'ID barang harus berupa angka.',
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
                'title' => 'Tambah Barang Keluar',
                'barang' => $barangModel->findAll(),
                'validation' => $this->validator,
            ];
            return view('barang_keluar/tambah', $data);
        }

        $barangId = $this->request->getPost('barang_id');
        $barang = $barangModel->find($barangId);

        // Validasi stok barang
        $jumlahKeluar = $this->request->getPost('jumlah_keluar');
        if ($jumlahKeluar > $barang['stok']) {
            session()->setFlashdata('error', 'Stok tidak mencukupi.');
            $data = [
                'title' => 'Tambah Barang Keluar',
                'barang' => $barangModel->findAll(),
                'validation' => $this->validator,
            ];
            return view('barang_keluar/tambah', $data);
        }
        $data = [
            'barang_id' => $barangId,
            'jumlah_keluar' => $jumlahKeluar,
            'tanggal_keluar' => $this->request->getPost('tanggal_keluar'),
            'kode_keluar' => $this->request->getPost('kode_keluar'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $barangKeluarModel->save($data);

        // Update stok barang
        $barang['stok'] -= $jumlahKeluar;
        $barangModel->save($barang);

        session()->setFlashdata('success', 'Barang keluar berhasil ditambahkan.');
        return redirect()->to('/barang_keluar');
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
            'kode_keluar' => 'required|alpha_numeric_space',
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
            'kode_keluar' => [
                'required' => 'Kode masuk harus diisi.',
                'alpha_numeric_space' => 'Kode masuk hanya boleh berisi huruf, angka, dan spasi.',
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
        return redirect()->to('/barang_keluar');
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
        return redirect()->to('/barang_keluar');
    }
}
