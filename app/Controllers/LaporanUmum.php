<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\BarangMasukModel;
use App\Models\BarangKeluarModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanUmum extends BaseController
{
    protected $barangMasukModel;
    protected $barangKeluarModel;

    public function __construct()
    {
        $this->barangMasukModel = new BarangMasukModel();
        $this->barangKeluarModel = new BarangKeluarModel();
    }

    public function index()
    {
        $barangModel = new BarangModel(); // Inisialisasi model BarangModel

        $data = [
            'title' => 'Laporan Umum',
            'barang' => $barangModel->findAll(), // Mengambil semua data barang
        ];
        return view('laporan/index', $data);
    }


    public function generate()
    {
        $barangModel = new BarangModel();

        $jenisLaporan = $this->request->getPost('jenis_laporan');
        if (is_array($jenisLaporan)) {
            $jenisLaporan = $jenisLaporan[0]; // Ambil nilai pertama jika berupa array
        }
        $periodeAwal = $this->request->getPost('periode_awal');
        $periodeAkhir = $this->request->getPost('periode_akhir');
        $barangId = intval($this->request->getPost('barang_id'));

        $validationRules = [
            'jenis_laporan' => 'required|in_list[masuk,keluar,stock]', // Tambahkan 'stock' sebagai pilihan valid
            'periode_awal' => 'required|valid_date',
            'periode_akhir' => 'required|valid_date',
        ];

        $validationMessages = [
            'jenis_laporan' => [
                'required' => 'Jenis laporan harus dipilih.',
                'in_list' => 'Jenis laporan tidak valid.',
            ],
            'periode_awal' => [
                'required' => 'Periode awal harus diisi.',
                'valid_date' => 'Format tanggal tidak valid.',
            ],
            'periode_akhir' => [
                'required' => 'Periode akhir harus diisi.',
                'valid_date' => 'Format tanggal tidak valid.',
            ],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            $data = [
                'title' => 'Laporan Umum',
                'barang' => $barangModel->findAll(),
                'validation' => $this->validator,
                'jenisLaporan' => old('jenis_laporan'),
                'periodeAwal' => old('periode_awal'),
                'periodeAkhir' => old('periode_akhir'),
                'barangId' => old('barang_id'),
            ];
            return view('laporan/index', $data);
        }

        $periodeAwal = is_array($periodeAwal) ? $periodeAwal[0] : $periodeAwal;
        $periodeAkhir = is_array($periodeAkhir) ? $periodeAkhir[0] : $periodeAkhir;

        $periodeAwalObj = \DateTime::createFromFormat('Y-m-d', $periodeAwal);
        $periodeAkhirObj = \DateTime::createFromFormat('Y-m-d', $periodeAkhir);

        if (!$periodeAwalObj || !$periodeAkhirObj || $periodeAkhirObj < $periodeAwalObj) {
            $data = [
                'title' => 'Laporan Umum',
                'barang' => $barangModel->findAll(),
                'jenisLaporan' => $jenisLaporan,
                'periodeAwal' => $periodeAwal,
                'periodeAkhir' => $periodeAkhir,
                'barangId' => $barangId,
            ];
            session()->setFlashdata('error', 'Periode akhir harus sama dengan atau setelah periode awal.');
            return view('laporan/index', $data);
        }

        // Ambil data sesuai jenis laporan
        if ($jenisLaporan === 'masuk') {
            $this->barangMasukModel->where('DATE(barang_masuk.tanggal_masuk) >=', $periodeAwal);
            $this->barangMasukModel->where('DATE(barang_masuk.tanggal_masuk) <=', $periodeAkhir);
            if ($barangId) {
                $this->barangMasukModel->where('barang_masuk.barang_id', $barangId);
            }
            $dataLaporan = $this->barangMasukModel->getLaporanBarangMasuk();
        } elseif ($jenisLaporan === 'keluar') {
            $this->barangKeluarModel->where('DATE(barang_keluar.tanggal_keluar) >=', $periodeAwal);
            $this->barangKeluarModel->where('DATE(barang_keluar.tanggal_keluar) <=', $periodeAkhir);
            if ($barangId) {
                $this->barangKeluarModel->where('barang_keluar.barang_id', $barangId);
            }
            $dataLaporan = $this->barangKeluarModel->getLaporanBarangKeluar();
        } elseif ($jenisLaporan === 'stock') { // Logika untuk jenis laporan stok
            // Ambil semua barang dari model
            $dataLaporan = $barangModel->findAll();
            // Tambahkan kunci 'keterangan' untuk setiap item data laporan stok
            foreach ($dataLaporan as &$item) {
                $item['keterangan'] = 'Stok saat ini: ' . $item['stok'] . ' ' . $item['satuan']; // Sesuaikan dengan struktur data Anda
                $item['tanggal_update'] = $item['updated_at']; 
            }
        }

        if (empty($dataLaporan)) {
            session()->setFlashdata('error', 'Data tidak ditemukan di database untuk periode dan jenis laporan yang dipilih.');
            $data = [
                'title' => 'Laporan Umum',
                'barang' => $barangModel->findAll(),
                'jenisLaporan' => $jenisLaporan,
                'periodeAwal' => $periodeAwal,
                'periodeAkhir' => $periodeAkhir,
                'barangId' => $barangId,
            ];
            return view('laporan/index', $data);
        }

        session()->setFlashdata('success', 'Laporan berhasil dibuat.');
        $data = [
            'title' => 'Laporan Barang ' . ucfirst($jenisLaporan),
            'laporan' => $dataLaporan,
            'barang' => $barangModel->findAll(),
            'jenisLaporan' => $jenisLaporan,
            'periodeAwal' => $periodeAwal,
            'periodeAkhir' => $periodeAkhir,
            'barangId' => $barangId,
        ];

        return view('laporan/index', $data);
    }



    public function exportPdf()
    {
        // Ambil jenis laporan, periode awal, dan periode akhir dari query string
        $jenisLaporan = $this->request->getGet('jenis_laporan');
        $periodeAwal = $this->request->getGet('periode_awal');
        $periodeAkhir = $this->request->getGet('periode_akhir');

        // Validasi jenis laporan (pastikan nilainya 'masuk', 'keluar', atau 'stock')
        if (!in_array($jenisLaporan, ['masuk', 'keluar', 'stock'])) {
            return redirect()->to('/laporan')->with('error', 'Jenis laporan tidak valid.');
        }
        if (is_string($periodeAwal)) {
            $periodeAwal = date('Y-m-d', strtotime($periodeAwal));
        }

        if (is_string($periodeAkhir)) {
            $periodeAkhir = date('Y-m-d', strtotime($periodeAkhir));
        }
        $total = 0;
        // Ambil data sesuai jenis laporan
        if ($jenisLaporan === 'masuk') {
            $dataLaporan = $this->barangMasukModel->getLaporanBarangMasuk($periodeAwal, $periodeAkhir);
        } elseif ($jenisLaporan === 'keluar') {
            $dataLaporan = $this->barangKeluarModel->getLaporanBarangKeluar($periodeAwal, $periodeAkhir);
        } elseif ($jenisLaporan === 'stock') {
            $barangModel = new BarangModel();
            $dataLaporan = $barangModel->findAll();

            // Tambahkan keterangan stok pada setiap item data laporan
            foreach ($dataLaporan as &$item) {
                $item['keterangan'] = 'Stok saat ini: ' . $item['stok'] . ' ' . $item['satuan']; // Sesuaikan dengan struktur data Anda
                $item['tanggal_update'] = $item['updated_at']; // Tambahkan tanggal update terakhir
                $total += $item['stok'];
            }
        }

        // Hitung total jumlah barang masuk/keluar
        
        foreach ($dataLaporan as $item) {
            if ($jenisLaporan === 'masuk') {
                $total += $item['jumlah_masuk'];
            } elseif ($jenisLaporan === 'keluar') {
                $total += $item['jumlah_keluar'];
            }
        }

        $data = [
            'laporan' => $dataLaporan,
            'jenisLaporan' => $jenisLaporan,
            'periodeAwal' => $periodeAwal,
            'periodeAkhir' => $periodeAkhir,
            'total' => $total,
        ];

        $dompdf = new \Dompdf\Dompdf();
        $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        
        $dompdf->loadHtml(view('laporan/pdf', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $stream = TRUE;
        if ($stream) {
            $dompdf->stream("laporan_barang_{$jenisLaporan}" . ".pdf", array("Attachment" => 0));
            exit();
        } else {
            return $dompdf->output();
        }
    }


    public function exportExcel()
    {
        // Ambil jenis laporan, periode awal, dan periode akhir dari query string
        $jenisLaporan = $this->request->getGet('jenis_laporan');
        $periodeAwal = $this->request->getGet('periode_awal');
        $periodeAkhir = $this->request->getGet('periode_akhir');

        // Validasi jenis laporan (pastikan nilainya 'masuk', 'keluar', atau 'stock')
        if (!in_array($jenisLaporan, ['masuk', 'keluar', 'stock'])) {
            return redirect()->to('/laporan')->with('error', 'Jenis laporan tidak valid.');
        }

        // Ambil data sesuai jenis laporan
        if ($jenisLaporan === 'masuk') {
            $dataLaporan = $this->barangMasukModel->getLaporanBarangMasuk($periodeAwal, $periodeAkhir);
        } elseif ($jenisLaporan === 'keluar') {
            $dataLaporan = $this->barangKeluarModel->getLaporanBarangKeluar($periodeAwal, $periodeAkhir);
        } elseif ($jenisLaporan === 'stock') {
            $barangModel = new BarangModel();
            $dataLaporan = $barangModel->findAll();

            // Tambahkan keterangan stok pada setiap item data laporan
            foreach ($dataLaporan as &$item) {
                $item['keterangan'] = 'Stok saat ini: ' . $item['stok'] . ' ' . $item['satuan']; // Sesuaikan dengan struktur data Anda
                $item['tanggal_update'] = $item['updated_at']; // Tambahkan tanggal update terakhir
            }
        }

        // Hitung total jumlah barang masuk/keluar
        $total = 0;
        foreach ($dataLaporan as $item) {
            if ($jenisLaporan === 'masuk') {
                $total += $item['jumlah_masuk'];
            } elseif ($jenisLaporan === 'keluar') {
                $total += $item['jumlah_keluar'];
            }
        }

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Styling header
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFFFFFFF', // Putih
                ],
            ],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle); // Terapkan style ke header

        // Style total row
        $totalStyle = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFDDDDDD', // Abu-abu
                ],
            ],
        ];

        // Isi data ke dalam sheet
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', ($jenisLaporan === 'masuk') ? 'Kode Masuk' : (($jenisLaporan === 'keluar') ? 'Kode Keluar' : ''));
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', ($jenisLaporan === 'masuk') ? 'Jumlah Masuk' : (($jenisLaporan === 'keluar') ? 'Jumlah Keluar' : 'Stok'));
        $sheet->setCellValue('E1', ($jenisLaporan === 'masuk') ? 'Tanggal Masuk' : (($jenisLaporan === 'keluar') ? 'Tanggal Keluar' : 'Tanggal Update'));
        $sheet->setCellValue('F1', 'Keterangan');

        $no = 1;
        $row = 2;
        foreach ($dataLaporan as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            if ($jenisLaporan === 'masuk') {
                $sheet->setCellValue('B' . $row, $item['kode_masuk']);
                $sheet->setCellValue('D' . $row, $item['jumlah_masuk']);
                $sheet->setCellValue('E' . $row, $item['tanggal_masuk']);
            } elseif ($jenisLaporan === 'keluar') {
                $sheet->setCellValue('B' . $row, $item['kode_keluar']);
                $sheet->setCellValue('D' . $row, $item['jumlah_keluar']);
                $sheet->setCellValue('E' . $row, $item['tanggal_keluar']);
            } elseif ($jenisLaporan === 'stock') {
                $sheet->setCellValue('B' . $row, ''); // Kosongkan kolom 'Kode Masuk'/'Kode Keluar' untuk jenis 'stock'
                $sheet->setCellValue('D' . $row, $item['stok']);
                $sheet->setCellValue('E' . $row, $item['tanggal_update']);
            }
            $sheet->setCellValue('C' . $row, $item['nama_barang']);
            $sheet->setCellValue('F' . $row, $item['keterangan']);
            $row++;
        }

        // Add total row
        if ($jenisLaporan === 'stock') {
            $sheet->setCellValue('C' . $row, 'Total');
            $sheet->setCellValue('D' . $row, ''); // Kosongkan kolom 'Jumlah Masuk'/'Jumlah Keluar' untuk jenis 'stock'
            $sheet->setCellValue('E' . $row, ''); // Kosongkan kolom 'Tanggal Masuk'/'Tanggal Keluar' untuk jenis 'stock'
            $sheet->setCellValue('F' . $row, ''); // Kosongkan kolom 'Keterangan' untuk jenis 'stock'
            $sheet->setCellValue('D' . $row, $total);
            $sheet->getStyle('C' . $row . ':F' . $row)->applyFromArray($totalStyle); // Terapkan style ke baris total
        } else {
            $sheet->setCellValue('C' . $row, 'Total');
            $sheet->setCellValue('D' . $row, $total);
            $sheet->getStyle('C' . $row . ':D' . $row)->applyFromArray($totalStyle); // Terapkan style ke baris total
        }

        // Set lebar kolom otomatis
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Prepare download
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="laporan_barang_' . $jenisLaporan . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Stream file
        $writer->save('php://output');
        exit();
    }

}
