<?php

namespace App\Controllers;

use App\Models\BarangKeluarModel;
use App\Models\BarangMasukModel;
use App\Models\BarangModel;
use App\Models\PenggunaModel;

class AuthController extends BaseController
{
    protected $penggunaModel;

    public function __construct()
    {
        $this->penggunaModel = new PenggunaModel();
    }

    public function login()
    {
        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation(),
        ];
        return view('auth/login', $data);
    }

    public function loginProcess()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $password = is_array($password) ? $password[0] : $password; 
        $pengguna = $this->penggunaModel->where('email', $email)->first();

        if ($pengguna && password_verify($password, $pengguna['password'])) {
            // Login berhasil
            session()->set('isLoggedIn', true);
            session()->set('pengguna', $pengguna);

            // Arahkan berdasarkan jabatan
            if ($pengguna['jabatan'] === 'admin') {
                $barangModel = new BarangModel();
                $barangMasukModel = new BarangMasukModel();
                $barangKeluarModel = new BarangKeluarModel();

                $data = [
                    'title' => 'Dashboard',
                    'jumlahBarang' => $barangModel->countAllResults(),
                    'jumlahBarangMasuk' => $barangMasukModel->countAllResults(),
                    'jumlahBarangKeluar' => $barangKeluarModel->countAllResults(),
                ];
                return redirect()->to('/')->with('data',
                    $data
                ); // Redirect ke halaman barang dengan data
            } elseif ($pengguna['jabatan'] === 'manager') {
                $barangModel = new BarangModel();

                $data = [
                    'title' => 'Laporan Umum',
                    'barang' => $barangModel->findAll(),
                ];
                return redirect()->to('/laporan')->with('data',
                    $data
                ); // Redirect ke halaman laporan dengan data
            } else {
                // Jika jabatan tidak valid, arahkan ke halaman login dengan pesan error
                session()->setFlashdata('error', 'Jabatan tidak valid.');
                return redirect()->to('/login');
            }
        }  else {
            // Login gagal
            session()->setFlashdata('error', 'Email atau password salah');
            $data = [
                'title' => 'Login',
                'validation' => \Config\Services::validation(),
            ];
            return view('auth/login', $data);
        }
    }

    public function logout()
    {
        session()->destroy(); // Hancurkan semua data sesi
        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation(),
        ];
        return view('auth/login', $data);
    }
}
