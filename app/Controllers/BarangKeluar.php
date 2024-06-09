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
        $data = [
            'title' => 'Tambah Barang Keluar',
            'barang' => $barangModel->findAll(),
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
        ];

        $barangKeluarModel->save($data);

        // Update stok barang
        $barang['stok'] -= $jumlahKeluar;
        $barangModel->save($barang);

        session()->setFlashdata('success', 'Barang keluar berhasil ditambahkan.');
        $data = [
            'title' => 'Barang Keluar',
            'barangKeluar' => $barangKeluarModel->getBarangKeluar(),
        ];
        return view('barang_keluar/index', $data);
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
                'title' => 'Edit Barang Keluar',
                'barangKeluar' => $barangKeluar,
                'barang' => $barangModel->findAll(),
                'validation' => $this->validator,
            ];
            return view('barang_keluar/edit', $data);
        }
        $barangId = $this->request->getPost('barang_id');
        $barang = $barangModel->find($barangId);

        // Validasi stok barang
        $jumlahKeluar = $this->request->getPost('jumlah_keluar');
        if ($jumlahKeluar > $barang['stok']) {
            session()->setFlashdata('error', 'Stok tidak mencukupi.');
            $data = [
                'title' => 'Edit Barang Keluar',
                'barang' => $barangModel->findAll(),
                'validation' => $this->validator,
            ];
            return view('barang_keluar/edit', $data);
        }

        // Hitung selisih jumlah keluar
        $jumlahKeluarLama = $barangKeluar['jumlah_keluar'];
        $jumlahKeluarBaru = $this->request->getPost('jumlah_keluar');
        $selisihJumlah = $jumlahKeluarBaru - $jumlahKeluarLama;

        $data = [
            'barang_id' => $this->request->getPost('barang_id'),
            'jumlah_keluar' => $jumlahKeluarBaru,
            'tanggal_keluar' => $this->request->getPost('tanggal_keluar'),
        ];

        $barangKeluarModel->update($id, $data);

        // Update stok barang
        $barangId = $this->request->getPost('barang_id');
        $barang = $barangModel->find($barangId);
        $barang['stok'] -= $selisihJumlah; // Kurangi stok jika jumlah keluar bertambah
        $barangModel->save($barang);

        session()->setFlashdata('success', 'Barang keluar berhasil diperbarui.');
        $data = [
            'title' => 'Barang Keluar',
            'barangKeluar' => $barangKeluarModel->getBarangKeluar(),
        ];
        return view('barang_keluar/index', $data);
    }
    public function hapus($id)
    {
        $barangKeluarModel = new BarangKeluarModel();
        $barangModel = new BarangModel();
        $barangKeluar = $barangKeluarModel->find($id);
        if (!$barangKeluar) {
            return redirect()->to('/barang-keluar')->with('error', 'Data barang keluar tidak ditemukan.');
        }

        // Update stok barang sebelum menghapus barang keluar
        $barang = $barangModel->find($barangKeluar['barang_id']);
        if ($barang) {
            $barang['stok'] += $barangKeluar['jumlah_keluar']; // Kembalikan stok yang keluar
            $barangModel->save($barang);
        }

        $barangKeluarModel->delete($id);

        session()->setFlashdata('success', 'Data barang keluar berhasil dihapus.');
        $data = [
            'title' => 'Barang Keluar',
            'barangKeluar' => $barangKeluarModel->getBarangKeluar(),
        ];
        return view('barang_keluar/index', $data);
    }
}
