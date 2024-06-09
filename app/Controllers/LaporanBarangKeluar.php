<?php

namespace App\Controllers;

use App\Models\LaporanBarangKeluarModel;
use App\Models\BarangKeluarModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanBarangKeluar extends BaseController
{

    public function index()
    {
        $laporanBarangKeluarModel = new LaporanBarangKeluarModel();
        $barangKeluarModel = new BarangKeluarModel(); // Inisialisasi model BarangKeluarModel

        $data = [
            'title' => 'Laporan Barang Keluar',
            'laporanBarangKeluar' => $laporanBarangKeluarModel->findAll(),
            'barangKeluar' => $barangKeluarModel->getBarangKeluar(), // Ambil semua data barang keluar
        ];

        return view('laporan_barang_keluar/index', $data);
    }

    public function generate()
    {
        $laporanBarangKeluarModel = new LaporanBarangKeluarModel();
        $barangKeluarModel = new BarangKeluarModel();

        // Validasi input periode
        $validationRules = [
            'periode_awal' => 'required|valid_date',
            'periode_akhir' => 'required|valid_date',
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('error', 'Input periode tidak valid.');
            $data = [
                'title' => 'Laporan Barang Keluar',
                'laporanBarangKeluar' => $laporanBarangKeluarModel->findAll(),
                'barangKeluar' => $barangKeluarModel->getBarangKeluar(), // Ambil semua data barang keluar
            ];

            return view('laporan_barang_keluar/index', $data);
        }

        $periodeAwal = $this->request->getPost('periode_awal');
        $periodeAkhir = $this->request->getPost('periode_akhir');
        if (is_array($periodeAwal)) {
            // Choose the first date in the array, or you might have another logic
            $periodeAwal = $periodeAwal[0];
        }

        if (is_array($periodeAkhir)) {
            $periodeAkhir = $periodeAkhir[0];
        }
        // Manual Date Comparison
        $periodeAwalObj = \DateTime::createFromFormat('Y-m-d', $periodeAwal);
        $periodeAkhirObj = \DateTime::createFromFormat('Y-m-d', $periodeAkhir);

        if (!$periodeAwalObj || !$periodeAkhirObj || $periodeAkhirObj < $periodeAwalObj) {
            session()->setFlashdata('error', 'Periode akhir harus sama dengan atau setelah periode awal.');
            $data = [
                'title' => 'Laporan Barang Keluar',
                'laporanBarangKeluar' => $laporanBarangKeluarModel->findAll(),
                'barangKeluar' => $barangKeluarModel->getBarangKeluar(), // Ambil semua data barang keluar
            ];

            return view('laporan_barang_keluar/index', $data);
        }

        // Fetch barang_keluar IDs within the specified period
        $barangKeluarIds = $barangKeluarModel
            ->select('id')
            ->where('DATE(tanggal_keluar) >=', $periodeAwal)
            ->where('DATE(tanggal_keluar) <=', $periodeAkhir)
            ->findAll();

        if (empty($barangKeluarIds)) {
            session()->setFlashdata('error', 'Tidak ada data barang keluar pada periode tersebut.');
            $data = [
                'title' => 'Laporan Barang Keluar',
                'laporanBarangKeluar' => $laporanBarangKeluarModel->findAll(),
                'barangKeluar' => $barangKeluarModel->getBarangKeluar(), // Ambil semua data barang keluar
            ];

            return view('laporan_barang_keluar/index', $data);
        }

        // Calculate total_barang_keluar based on the fetched IDs
        $totalBarangKeluar = $barangKeluarModel->whereIn('id', array_column($barangKeluarIds, 'id'))->selectSum('jumlah_keluar')->first()['jumlah_keluar'];

        // Hapus laporan yang sudah ada untuk periode yang sama
        $laporanBarangKeluarModel
            ->where('periode_awal', $periodeAwal)
            ->where('periode_akhir', $periodeAkhir)
            ->delete();

        // Create separate laporan entries for each barang_keluar_id
        foreach ($barangKeluarIds as $item) {
            $dataLaporan = [
                'barang_keluar_id' => $item['id'],
                'periode_awal' => $periodeAwal,
                'periode_akhir' => $periodeAkhir,
                'total_barang_keluar' => $totalBarangKeluar,
            ];

            $laporanBarangKeluarModel->insert($dataLaporan);
        }

        // Mengambil data barang keluar yang sesuai dengan periode
        $barangKeluar = $barangKeluarModel
            ->select('barang_keluar.*, barang.nama_barang')
            ->join('barang', 'barang.id = barang_keluar.barang_id')
            ->where('DATE(tanggal_keluar) >=', $periodeAwal)
            ->where('DATE(tanggal_keluar) <=', $periodeAkhir)
            ->findAll();

        // Mengirimkan data laporan dan barang keluar ke view
        $data = [
            'title' => 'Laporan Barang Keluar',
            'laporanBarangKeluar' => $laporanBarangKeluarModel->findAll(),
            'barangKeluar' => $barangKeluar, // Data barang keluar yang difilter
        ];

        session()->setFlashdata('success', 'Laporan barang keluar berhasil dibuat.');
        return view('laporan_barang_keluar/index', $data);
    }
    public function exportPdf()
    {
        // Get the periode_awal and periode_akhir from the query string
        $periodeAwal = $this->request->getGet('periode_awal');
        $periodeAkhir = $this->request->getGet('periode_akhir');

        $barangKeluarModel = new BarangKeluarModel();

        // Build the query
        $builder = $barangKeluarModel
            ->select('barang_keluar.*, barang.nama_barang')
            ->join('barang', 'barang.id = barang_keluar.barang_id');

        // Apply date filters only if they are provided
        if ($periodeAwal) {
            $builder->where('DATE(tanggal_keluar) >=', $periodeAwal);
        }
        if ($periodeAkhir) {
            $builder->where('DATE(tanggal_keluar) <=', $periodeAkhir);
        }

        // Get the results
        $barangKeluar = $builder->findAll();

        $data = [
            'barangKeluar' => $barangKeluar,
            'periodeAwal' => $periodeAwal,
            'periodeAkhir' => $periodeAkhir,
        ];

        // Load library PDF (misalnya, dompdf)
        $dompdf = new \Dompdf\Dompdf();
        $dompdf = new Dompdf(['isHtml5ParserEnabled' => true]);
        $dompdf->loadHtml(view('laporan_barang_keluar/pdf', $data));
        $dompdf->setPaper('A4', 'landscape'); // Opsional, sesuaikan ukuran kertas dan orientasi
        $dompdf->render();

        // Tambahkan header untuk memaksa unduhan
        $filename = 'laporan_barang_keluar.pdf';
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $dompdf->stream($filename, ['Attachment' => 1]); // 1 untuk download, 0 untuk menampilkan di browser
    }


    public function exportExcel()
    {
        // Get the periode_awal and periode_akhir from the query string
        $periodeAwal = $this->request->getGet('periode_awal');
        $periodeAkhir = $this->request->getGet('periode_akhir');

        $barangKeluarModel = new BarangKeluarModel();

        // Build the query
        $builder = $barangKeluarModel
            ->select('barang_keluar.*, barang.nama_barang')
            ->join('barang', 'barang.id = barang_keluar.barang_id');

        // Apply date filters only if they are provided
        if ($periodeAwal) {
            $builder->where('DATE(tanggal_keluar) >=', $periodeAwal);
        }
        if ($periodeAkhir) {
            $builder->where('DATE(tanggal_keluar) <=', $periodeAkhir);
        }

        // Get the results
        $barangKeluar = $builder->findAll();

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

        // Isi data ke dalam sheet
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Keluar');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Jumlah Keluar');
        $sheet->setCellValue('E1', 'Tanggal Keluar');
        $sheet->setCellValue('F1', 'Keterangan');

        $no = 1;
        $row = 2;
        foreach ($barangKeluar as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['kode_keluar']);
            $sheet->setCellValue('C' . $row, $item['nama_barang']);
            $sheet->setCellValue('D' . $row, $item['jumlah_keluar']);
            $sheet->setCellValue('E' . $row, $item['tanggal_keluar']);
            $sheet->setCellValue('F' . $row, $item['keterangan']);
            $row++;
        }

        // Set lebar kolom otomatis
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $filename = 'laporan_barang_keluar.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
