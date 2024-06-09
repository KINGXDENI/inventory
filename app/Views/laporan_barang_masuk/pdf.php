<!DOCTYPE html>
<html>

<head>
    <title>Laporan Barang Masuk</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            /* Pastikan body mengisi tinggi viewport minimal */
        }

        .content {
            flex: 1;
            /* Membuat konten mengisi ruang yang tersedia */
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 50%;
            /* Posisikan di tengah */
            transform: translateX(-50%);
            /* Geser ke kiri sebesar setengah lebarnya */
            width: fit-content;
            /* Lebar sesuai konten */
            text-align: center;
            padding: 10px 0;
            font-size: 12px;
            border-top: 1px solid #ddd;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .signature {
            position: absolute;
            bottom: 20px;
            right: 20px;
            /* Jarak dari kanan halaman */
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="content">
        <h2>Laporan Barang Masuk</h2>
        <p>Periode: <?= $periodeAwal ?> - <?= $periodeAkhir ?></p>
        <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Masuk</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Masuk</th>
                    <th>Tanggal Masuk</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($barangMasuk as $item) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $item['kode_masuk'] ?></td>
                        <td><?= $item['nama_barang'] ?></td>
                        <td><?= $item['jumlah_masuk'] ?></td>
                        <td><?= $item['tanggal_masuk'] ?></td>
                        <td><?= $item['keterangan'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="signature">
            <p>Tanda Tangan:</p>
            <br>
            <br>
            <p>(____________________)</p>
        </div>

        <div class="footer">
            <p>Sistem Inventory Barang - Halaman {PAGENO} dari {nbpg}</p>
        </div>
    </div>
</body>

</html>