<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\BarangMasukModel;
use App\Models\BarangKeluarModel;

class Barang extends BaseController
{

    public function index()
    {
        $barangModel = new BarangModel();
        $barangMasukModel = new BarangMasukModel();
        $barangKeluarModel = new BarangKeluarModel();

        $data = [
            'title' => 'Dashboard', // Judul halaman
            'jumlahBarang' => $barangModel->countAllResults(), // Jumlah total barang
            'jumlahBarangMasuk' => $barangMasukModel->countAllResults(), // Jumlah total barang masuk
            'jumlahBarangKeluar' => $barangKeluarModel->countAllResults(), // Jumlah total barang keluar
        ];

        return view('barang/index', $data);
    }

    public function barang()
    {
        $barangModel = new BarangModel();

        $data = [
            'title' => 'Data Barang', // Judul halaman
            'barang' => $barangModel->findAll(),
        ];

        return view('barang/barang', $data);
    }
    public function tambahbarang()
    {
        $barangModel = new BarangModel();

        // Ambil data barang terakhir yang memiliki kode_barang sesuai format
        $lastBarang = $barangModel->like('kode_barang', 'BR-', 'after')->orderBy('kode_barang', 'desc')->first();

        if ($lastBarang) {
            $lastNoUrut = (int) substr($lastBarang['kode_barang'], 3); // Ambil nomor urut (integer)
            $noUrut = $lastNoUrut + 1;
        } else {
            $noUrut = 1; // Jika belum ada barang, mulai dari 1
        }

        $newKode = 'BR-' . sprintf('%03d', $noUrut);
        $data = [
            'title' => 'Tambah Barang',
            'kodeBarang' => $newKode,
            'validation' => \Config\Services::validation(),
        ];

        return view('barang/barangtambah', $data);
    }

    public function simpan()
    {
        $validationRules = [
            'nama_barang' => 'required',
            'foto' => 'uploaded[foto]|ext_in[foto,jpg,jpeg,png]|max_size[foto,2048]',
            'merek' => 'required', // Menambahkan aturan validasi untuk merek
        ];

        $validationMessages = [
            'nama_barang' => [
                'required' => 'Nama barang harus diisi.',
            ],
            'foto' => [
                'uploaded' => 'Gambar harus diunggah.',
                'ext_in' => 'Format gambar tidak valid. Hanya jpg, jpeg, dan png yang diperbolehkan.',
                'max_size' => 'Ukuran gambar terlalu besar. Maksimal 2MB.',
            ],
            'merek' => [
                'required' => 'Merek harus diisi.', // Pesan validasi untuk merek
            ],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            $data = [
                'title' => 'Tambah Barang',
                'kodeBarang' => $this->request->getPost('kode_barang'), // Mengisi kembali kode barang
                'validation' => $this->validator,
            ];
            return view('barang/barangtambah', $data);
        }

        $barangModel = new BarangModel();

        $uploadedFile = $this->request->getFile('foto');
        if ($uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            $newName = $uploadedFile->getRandomName();
            $uploadedFile->move('barangimg', $newName);

            $data = [
                'kode_barang' => $this->request->getPost('kode_barang'),
                'nama_barang' => $this->request->getPost('nama_barang'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'foto' => $newName,
                'stok' => $this->request->getPost('stok'),
                'berat' => $this->request->getPost('berat'),
                'satuan' => $this->request->getPost('satuan'),
                'merek' => $this->request->getPost('merek'), // Menambahkan merek ke dalam data yang disimpan
            ];

            $barangModel->save($data);
            session()->setFlashdata('success', 'Barang berhasil ditambahkan.');
            return redirect()->to('/barang');
        } else {
            session()->setFlashdata('error', 'Gagal mengupload gambar.');
            $data = [
                'title' => 'Tambah Barang',
                'kodeBarang' => $this->request->getPost('kode_barang'), // Mengisi kembali kode barang
            ];
            return view('barang/barangtambah', $data);
        }
    }



    public function editbarang($id)
    {
        $barangModel = new BarangModel();

        $barang = $barangModel->find($id);
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Barang',
            'barang' => $barang, // Pastikan $barang didefinisikan di sini
            'validation' => \Config\Services::validation(),
        ];

        return view('barang/barangedit', $data);
    }

    public function update($id)
    {
        $barangModel = new BarangModel();

        $validationRules = [
            'nama_barang' => 'required',
            'foto' => 'if_exist|ext_in[foto,jpg,jpeg,png]|max_size[foto,2048]',
            'merek' => 'required', // Menambahkan aturan validasi untuk merek
        ];

        $validationMessages = [
            'nama_barang' => [
                'required' => 'Nama barang harus diisi.',
            ],
            'foto' => [
                'uploaded' => 'Gambar harus diunggah.',
                'ext_in' => 'Format gambar tidak valid. Hanya jpg, jpeg, dan png yang diperbolehkan.',
                'max_size' => 'Ukuran gambar terlalu besar. Maksimal 2MB.',
            ],
            'merek' => [
                'required' => 'Merek harus diisi.', // Pesan validasi untuk merek
            ],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            $data = [
                'title' => 'Edit Barang',
                'barang' => $barangModel->find($id),
                'validation' => $this->validator,
            ];
            return view('barang/barangedit', $data);
        }

        $uploadedFile = $this->request->getFile('foto');
        $fotoLama = $this->request->getPost('fotoLama');

        if ($uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            $newName = $uploadedFile->getRandomName();
            $uploadedFile->move('barangimg', $newName);

            // Hapus foto lama jika ada
            if ($fotoLama && file_exists('barangimg/' . $fotoLama)) {
                unlink('barangimg/' . $fotoLama);
            }

            $foto = $newName;
        } else {
            $foto = $fotoLama;
        }

        $data = [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'foto' => $foto,
            'stok' => $this->request->getPost('stok'),
            'berat' => $this->request->getPost('berat'),
            'satuan' => $this->request->getPost('satuan'),
            'merek' => $this->request->getPost('merek'), // Menambahkan merek ke dalam data yang disimpan
        ];

        $barangModel->update($id, $data);

        session()->setFlashdata('success', 'Barang berhasil diperbarui.');
        return redirect()->to('/barang');
    }


    public function delete($id)
    {
        $barangMasukModel = new BarangMasukModel();
        $barangKeluarModel = new BarangKeluarModel();
        $barangModel = new BarangModel();
        $barang = $barangModel->find($id);
        if (!$barang) {
            session()->setFlashdata('error', 'Barang tidak ditemukan.');
            $data = [
                'title' => 'Data Barang', // Judul halaman
                'barang' => $barangModel->findAll(),
            ];

            return view('barang/barang', $data);
        }

        // Hapus foto jika ada

        if ($barang['foto'] && file_exists('public/barangimg/' . $barang['foto'])) {
            unlink('public/barangimg/' . $barang['foto']);
        }
        $barangKeluarModel->where('barang_id', $id)->delete();
        $barangMasukModel->where('barang_id', $id)->delete();
        $barangModel->delete($id);

        session()->setFlashdata('success', 'Barang berhasil dihapus.');
        return redirect()->to('/barang');
    }
}
