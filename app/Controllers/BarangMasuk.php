<?php

namespace App\Controllers;

use App\Models\BarangMasukModel;
use App\Models\BarangModel;

class BarangMasuk extends BaseController
{
    public function index()
    {
        $barangMasukModel = new BarangMasukModel(); // Inisialisasi di dalam method
        $data = [
            'title' => 'Barang Masuk',
            'barangMasuk' => $barangMasukModel->getBarangMasuk(),
        ];
        return view('barang_masuk/index', $data);
    }

    public function tambah()
    {
        $barangModel = new BarangModel(); // Inisialisasi di dalam method
        $barangMasukModel = new BarangMasukModel(); // Inisialisasi di dalam method
        $lastBarang = $barangMasukModel->like('kode_masuk', 'BM-', 'after')->orderBy('kode_masuk', 'desc')->first();

        if ($lastBarang) {
            $lastNoUrut = (int) substr($lastBarang['kode_masuk'], 3); // Ambil nomor urut (integer)
            $noUrut = $lastNoUrut + 1;
        } else {
            $noUrut = 1; // Jika belum ada barang, mulai dari 1
        }

        $newKode = 'BM-' . sprintf('%03d', $noUrut);
        $data = [
            'title' => 'Tambah Barang Masuk',
            'barang' => $barangModel->findAll(),
            'kodeMasuk' => $newKode,
            'validation' => \Config\Services::validation(),
        ];
        return view('barang_masuk/tambah', $data);
    }

    public function simpan()
    {
        $barangMasukModel = new BarangMasukModel();
        $barangModel = new BarangModel();

        $validationRules = [
            'barang_id.*' => 'required|integer', // Validasi untuk array barang_id
            'jumlah_masuk.*' => 'required|greater_than[0]', // Validasi untuk array jumlah_masuk
            'kode_masuk' => 'required', // Validasi untuk kode_masuk
            'tanggal_masuk' => 'required|valid_date[Y-m-d\TH:i]', // Validasi untuk tanggal_masuk
            'keterangan.*' => 'permit_empty', // Keterangan bersifat opsional
        ];

        $validationMessages = [
            'barang_id.*' => [
                'required' => 'Pilih barang yang masuk.',
                'integer' => 'ID barang harus berupa angka.',
            ],
            'jumlah_masuk.*' => [
                'required' => 'Jumlah masuk harus diisi.',
                'greater_than' => 'Jumlah masuk harus lebih dari 0.',
            ],
            'kode_masuk' => [
                'required' => 'Kode masuk harus diisi.',
            ],
            'tanggal_masuk' => [
                'required' => 'Tanggal masuk harus diisi.',
                'valid_date' => 'Format tanggal dan waktu tidak valid.',
            ],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            $data = [
                'title' => 'Tambah Barang Masuk',
                'barang' => $barangModel->findAll(),
                'kodeMasuk' => $this->request->getPost('kode_masuk'), 
                'validation' => $this->validator,
            ];
            return view('barang_masuk/tambah', $data);
        }

        // Proses penyimpanan data
        $inputs = $this->request->getPost();

        // Loop untuk menyimpan setiap barang masuk
        foreach ($inputs['barang_id'] as $key => $barangId) {
            $data = [
                'barang_id' => $barangId,
                'jumlah_masuk' => $inputs['jumlah_masuk'][$key],
                'tanggal_masuk' => $inputs['tanggal_masuk'],
                'kode_masuk' => $inputs['kode_masuk'],
                'keterangan' => isset($inputs['keterangan'][$key]) ? $inputs['keterangan'][$key] : null,
            ];

            $barangMasukModel->save($data);

            // Update stok barang
            $barang = $barangModel->find($barangId);
            $barang['stok'] += $data['jumlah_masuk'];
            $barangModel->save($barang);
        }

        session()->setFlashdata('success', 'Barang masuk berhasil ditambahkan.');
        return redirect()->to('/barang-masuk');
    }



    public function edit($id)
    {
        $barangMasukModel = new BarangMasukModel();
        $barangModel = new BarangModel();
        $barangMasuk = $barangMasukModel->find($id);
        if (!$barangMasuk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Barang Masuk',
            'barangMasuk' => $barangMasuk,
            'barang' => $barangModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('barang_masuk/edit', $data);
    }

    public function update($id)
    {
        $barangMasukModel = new BarangMasukModel();
        $barangModel = new BarangModel();
        $barangMasuk = $barangMasukModel->find($id);

        if (!$barangMasuk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validationRules = [
            'barang_id' => 'required|integer', // Memastikan barang_id valid dan ada di tabel barang
            'jumlah_masuk' => 'required|greater_than[0]', // Memastikan jumlah_masuk lebih dari 0
            'tanggal_masuk' => 'required|valid_date[Y-m-d\TH:i]', // Memastikan tanggal_masuk valid
            'keterangan' => 'permit_empty', // Keterangan bersifat opsional
        ];

        $validationMessages = [
            'barang_id' => [
                'required' => 'Pilih barang yang masuk.',
                'integer' => 'ID barang harus berupa angka.',
            ],
            'jumlah_masuk' => [
                'required' => 'Jumlah masuk harus diisi.',
                'greater_than' => 'Jumlah masuk harus lebih dari 0.',
            ],
            'tanggal_masuk' => [
                'required' => 'Tanggal masuk harus diisi.',
                'valid_date' => 'Format tanggal dan waktu tidak valid.',
            ],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            $data = [
                'title' => 'Edit Barang Masuk',
                'barangMasuk' => $barangMasuk,
                'barang' => $barangModel->findAll(),
                'validation' => $this->validator,
            ];
            return view('barang_masuk/edit', $data);
        }

        // Hitung selisih jumlah masuk
        $selisihJumlah = $this->request->getPost('jumlah_masuk') - $barangMasuk['jumlah_masuk'];

        $data = [
            'barang_id' => $this->request->getPost('barang_id'),
            'jumlah_masuk' => $this->request->getPost('jumlah_masuk'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'kode_masuk' => $this->request->getPost('kode_masuk'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $barangMasukModel->update($id, $data);

        // Update stok barang
        $barangId = $this->request->getPost('barang_id');
        $barang = $barangModel->find($barangId);
        $barang['stok'] += $selisihJumlah;
        $barangModel->save($barang);

        session()->setFlashdata('success', 'Barang masuk berhasil diperbarui.');
        return redirect()->to('/barang-masuk');
    }

    public function hapus($id)
    {
        $barangMasukModel = new BarangMasukModel();
        $barangModel = new BarangModel();
        $barangMasuk = $barangMasukModel->find($id);

        if (!$barangMasuk) {
            session()->setFlashdata('error', 'Data barang masuk tidak ditemukan.');
            $data = [
                'title' => 'Barang Masuk',
                'barangMasuk' => $barangMasukModel->getBarangMasuk(),
            ];

            return view('barang_masuk/index', $data);
        }

        // Update stok barang sebelum menghapus barang masuk
        $barang = $barangModel->find($barangMasuk['barang_id']);
        if ($barang) {
            $barang['stok'] -= $barangMasuk['jumlah_masuk'];
            $barangModel->save($barang);
        }

        $barangMasukModel->delete($id);

        session()->setFlashdata('success', 'Data barang masuk berhasil dihapus.');
        return redirect()->to('/barang-masuk');
    }
}
