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
        $data = [
            'title' => 'Tambah Barang Masuk',
            'barang' => $barangModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];
        return view('barang_masuk/tambah', $data);
    }

    public function simpan()
    {
        $barangMasukModel = new BarangMasukModel();
        $barangModel = new BarangModel();

        $validationRules = [
            'barang_id' => 'required|integer', // Memastikan barang_id valid dan ada di tabel barang
            'jumlah_masuk' => 'required|greater_than[0]', // Memastikan jumlah_masuk lebih dari 0
            'tanggal_masuk' => 'required|valid_date[Y-m-d\TH:i]', // Memastikan tanggal_masuk valid
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
                'title' => 'Tambah Barang Masuk',
                'barang' => $barangModel->findAll(),
                'validation' => $this->validator,
            ];
            return view('barang_masuk/tambah', $data);
        }
        $data = [
            'barang_id' => $this->request->getPost('barang_id'),
            'jumlah_masuk' => $this->request->getPost('jumlah_masuk'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
        ];

        $barangMasukModel->save($data);

        // Update stok barang
        $barangId = $this->request->getPost('barang_id');
        $barang = $barangModel->find($barangId);
        $barang['stok'] += $data['jumlah_masuk'];
        $barangModel->save($barang);

        session()->setFlashdata('success', 'Barang masuk berhasil ditambahkan.');
        $data = [
            'title' => 'Barang Masuk',
            'barangMasuk' => $barangMasukModel->getBarangMasuk(),
        ];
        // Ambil data barang masuk setelah disimpan
      
        return view('barang_masuk/index', $data); // Tampilkan view index setelah berhasil menyimpan data
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
            'barang_id' => 'required|integer',
            'jumlah_masuk' => 'required|greater_than[0]',
            'tanggal_masuk' => 'required|valid_date[Y-m-d\TH:i]',
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
            return redirect()->to('/barang-masuk')->with('error', 'Data barang masuk tidak ditemukan.');
        }

        // Update stok barang sebelum menghapus barang masuk
        $barang = $barangModel->find($barangMasuk['barang_id']);
        if ($barang) {
            $barang['stok'] -= $barangMasuk['jumlah_masuk'];
            $barangModel->save($barang);
        }

        $barangMasukModel->delete($id);

        session()->setFlashdata('success', 'Data barang masuk berhasil dihapus.');
        $data = [
            'title' => 'Barang Masuk',
            'barangMasuk' => $barangMasukModel->getBarangMasuk(),
        ];
        return view('barang_masuk/index', $data);
    }

}
