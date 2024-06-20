<!DOCTYPE html>
<html>

<head>
    <title>Laporan Barang <?= ucfirst($jenisLaporan) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <h2>Laporan Barang <?= ucfirst($jenisLaporan) ?></h2>
    <p>Periode: <?= $periodeAwal ?> - <?= $periodeAkhir ?></p>
    <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <?php if ($jenisLaporan === 'masuk') : ?>
                    <th>Kode Masuk</th>
                <?php else : ?>
                    <th>Kode Keluar</th>
                <?php endif; ?>
                <th>Nama Barang</th>
                <th>Jumlah <?= ucfirst($jenisLaporan) ?></th>
                <th>Tanggal <?= ucfirst($jenisLaporan) ?></th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($laporan as $item) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $item[$jenisLaporan === 'masuk' ? 'kode_masuk' : 'kode_keluar'] ?></td>
                    <td><?= $item['nama_barang'] ?></td>
                    <td><?= $item[$jenisLaporan === 'masuk' ? 'jumlah_masuk' : 'jumlah_keluar'] ?></td>
                    <td><?= $item[$jenisLaporan === 'masuk' ? 'tanggal_masuk' : 'tanggal_keluar'] ?></td>
                    <td><?= $item['keterangan'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align: right;">Total</th>
                <th><?= $total ?></th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
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
</body>

</html>