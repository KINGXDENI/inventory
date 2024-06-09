<?php

namespace App\Controllers;

use App\Models\LaporanBarangMasukModel;
use App\Models\BarangMasukModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanBarangMasuk extends BaseController
{

    public function index()
    {
        $laporanBarangMasukModel = new LaporanBarangMasukModel();
        $barangMasukModel = new BarangMasukModel();
        $data = [
            'title' => 'Laporan Barang Masuk',
            'laporanBarangMasuk' => $laporanBarangMasukModel->findAll(),
            'barangMasuk' => $barangMasukModel->getBarangMasuk(), // Ambil semua data barang masuk
        ];
        return view('laporan_barang_masuk/index', $data);
    }

    public function generate()
    {
        $laporanBarangMasukModel = new LaporanBarangMasukModel();
        $barangMasukModel = new BarangMasukModel();
       

        // Validasi input periode
        $validationRules = [
            'periode_awal' => 'required|valid_date',
            'periode_akhir' => 'required|valid_date',
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('error', 'Input periode tidak valid.');
            $data = [
                'title' => 'Laporan Barang Masuk',
                'laporanBarangMasuk' => $laporanBarangMasukModel->findAll(),
                'barangMasuk' => $barangMasukModel->findAll(), // Ambil semua data barang masuk
            ];
            return view('laporan_barang_masuk/index', $data);
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
                'title' => 'Laporan Barang Masuk',
                'laporanBarangMasuk' => $laporanBarangMasukModel->findAll(),
                'barangMasuk' => $barangMasukModel->findAll(), // Ambil semua data barang masuk
            ];
            return view('laporan_barang_masuk/index', $data);
        }

        // Fetch barang_masuk IDs within the specified period
        $barangMasukIds = $barangMasukModel
            ->select('id')
            ->where('DATE(tanggal_masuk) >=', $periodeAwal)
            ->where('DATE(tanggal_masuk) <=', $periodeAkhir)
            ->findAll();

        if (empty($barangMasukIds)) {
            session()->setFlashdata('error', 'Tidak ada data barang masuk pada periode tersebut.');
            $data = [
                'title' => 'Laporan Barang Masuk',
                'laporanBarangMasuk' => $laporanBarangMasukModel->findAll(),
                'barangMasuk' => $barangMasukModel->findAll(), // Ambil semua data barang masuk
            ];
            return view('laporan_barang_masuk/index', $data);
        }

        // Calculate total_barang_masuk based on the fetched IDs
        $totalBarangMasuk = $barangMasukModel
            ->whereIn('id', array_column($barangMasukIds, 'id'))
            ->selectSum('jumlah_masuk')
            ->first()['jumlah_masuk'];

        // Hapus laporan yang sudah ada untuk periode yang sama
        $laporanBarangMasukModel
            ->where('periode_awal', $periodeAwal)
            ->where('periode_akhir', $periodeAkhir)
            ->delete();

        // Create separate laporan entries for each barang_masuk_id
        foreach ($barangMasukIds as $item) {
            $dataLaporan = [
                'barang_masuk_id' => $item['id'],
                'periode_awal' => $periodeAwal,
                'periode_akhir' => $periodeAkhir,
                'total_barang_masuk' => $totalBarangMasuk,
            ];

            $laporanBarangMasukModel->insert($dataLaporan);
        }

        // Ambil data barang masuk yang sesuai dengan periode (disertai nama barang)
        $barangMasuk = $barangMasukModel
            ->select('barang_masuk.*, barang.nama_barang')
            ->join('barang', 'barang.id = barang_masuk.barang_id')
            ->where('DATE(tanggal_masuk) >=', $periodeAwal)
            ->where('DATE(tanggal_masuk) <=', $periodeAkhir)
            ->findAll();

        // Mengirimkan data laporan dan barang masuk ke view
        $data = [
            'title' => 'Laporan Barang Masuk',
            'laporanBarangMasuk' => $laporanBarangMasukModel->findAll(),
            'barangMasuk' => $barangMasuk, // Data barang masuk yang difilter
        ];

        session()->setFlashdata('success', 'Laporan barang masuk berhasil dibuat.');
        return view('laporan_barang_masuk/index', $data); // Tampilkan view index setelah berhasil menyimpan data
    }

    public function exportPdf($periodeAwal = null, $periodeAkhir = null)
    {
        $periodeAwal = $this->request->getGet('periode_awal');
        $periodeAkhir = $this->request->getGet('periode_akhir');

        $barangMasukModel = new BarangMasukModel();
        // Ambil data barang masuk sesuai periode
        $builder = $barangMasukModel
            ->select('barang_masuk.*, barang.nama_barang')
            ->join('barang', 'barang.id = barang_masuk.barang_id');

        if ($periodeAwal) {
            $builder->where('DATE(tanggal_masuk) >=', $periodeAwal);
        }
        if ($periodeAkhir) {
            $builder->where('DATE(tanggal_masuk) <=', $periodeAkhir);
        }

        // Get the results
        $barangMasuk = $builder->findAll();

        $data = [
            'barangMasuk' => $barangMasuk,
            'periodeAwal' => $periodeAwal,
            'periodeAkhir' => $periodeAkhir,
        ];


        $dompdf = new \Dompdf\Dompdf();
        $dompdf = new Dompdf(['isHtml5ParserEnabled' => true]);
        $dompdf->loadHtml(view('laporan_barang_masuk/pdf', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan_barang_masuk.pdf');
    }

    public function exportExcel()
    {
        $barangMasukModel = new BarangMasukModel();
        // Get the periode_awal and periode_akhir from the query string
        $periodeAwal = $this->request->getGet('periode_awal');
        $periodeAkhir = $this->request->getGet('periode_akhir');

        $builder = $barangMasukModel
            ->select('barang_masuk.*, barang.nama_barang')
            ->join('barang', 'barang.id = barang_masuk.barang_id');

        if ($periodeAwal) {
            $builder->where('DATE(tanggal_masuk) >=', $periodeAwal);
        }
        if ($periodeAkhir) {
            $builder->where('DATE(tanggal_masuk) <=', $periodeAkhir);
        }

        // Get the results
        $barangMasuk = $builder->findAll();

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
        $sheet->setCellValue('B1', 'Kode Masuk');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Jumlah Masuk');
        $sheet->setCellValue('E1', 'Tanggal Masuk');
        $sheet->setCellValue('F1', 'Keterangan');

        $no = 1;
        $row = 2;
        foreach ($barangMasuk as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['kode_masuk']);
            $sheet->setCellValue('C' . $row, $item['nama_barang']);
            $sheet->setCellValue('D' . $row, $item['jumlah_masuk']);
            $sheet->setCellValue('E' . $row, $item['tanggal_masuk']);
            $sheet->setCellValue('F' . $row, $item['keterangan']);
            $row++;
        }

        // Set lebar kolom otomatis
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="laporan_barang_masuk.xlsx"');
        $writer->save('php://output');
    }
}
