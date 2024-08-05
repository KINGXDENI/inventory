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
            position: relative;
            margin-top: 50px;
            /* Add space between the table and the signature */
            float: right;
            /* Align signature to the right */
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 12px;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 100px;
        }
    </style>
</head>

<body>
    <img src="data:image/png;base64,<?= base64_encode(file_get_contents('assets/img/logo1.png')) ?>" alt="Logo" class="logo">

    <h2>Laporan Barang <?= ucfirst($jenisLaporan) ?></h2>
    <p>Periode: <?= $periodeAwal ?> - <?= $periodeAkhir ?></p>
    <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <?php if ($jenisLaporan === 'masuk') : ?>
                    <th>Kode Masuk</th>
                <?php elseif ($jenisLaporan === 'keluar') : ?>
                    <th>Kode Keluar</th>
                <?php elseif ($jenisLaporan === 'stock') : ?>
                    <th>Kode Barang</th>
                <?php endif; ?>
                <th>Nama Barang</th>
                <th>Jumlah <?= ucfirst($jenisLaporan) ?></th>
                <th>Tanggal <?= ucfirst($jenisLaporan) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($laporan as $item) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <?php if ($jenisLaporan === 'stock') : ?>
                        <td><?= $item['kode_barang'] ?></td>
                    <?php else : ?>
                        <td><?= $item[$jenisLaporan === 'masuk' ? 'kode_masuk' : 'kode_keluar'] ?></td>
                    <?php endif; ?>
                    <td><?= $item['nama_barang'] ?></td>
                    <td>
                        <?php
                        if ($jenisLaporan === 'masuk') {
                            echo $item['jumlah_masuk'];
                        } elseif ($jenisLaporan === 'keluar') {
                            echo $item['jumlah_keluar'];
                        } elseif ($jenisLaporan === 'stock') {
                            echo $item['stok'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($jenisLaporan === 'masuk') {
                            echo date('Y-m-d', strtotime($item['tanggal_masuk']));
                        } elseif ($jenisLaporan === 'keluar') {
                            echo date('Y-m-d', strtotime($item['tanggal_keluar']));
                        } elseif ($jenisLaporan === 'stock') {
                            echo date('Y-m-d', strtotime($item['tanggal_update'])); // Assuming this field exists for stock
                        }
                        ?>
                    </td>
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
        <p> Petugas Gudang </p>
    </div>
</body>

</html>