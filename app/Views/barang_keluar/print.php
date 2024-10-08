<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 100px;
        }

        .signatures {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }

        .signature {
            display: inline-block;
            width: 45%;
            text-align: center;
        }

        .signature.left {
            float: left;
            text-align: left;
        }

        .signature.right {
            float: right;
            text-align: right;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    <img src="data:image/png;base64,<?= base64_encode(file_get_contents('assets/img/logo1.png')) ?>" alt="Logo" class="logo">
    <h2>Nota Barang Keluar</h2>
    <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>

    <?php
    // Kelompokkan data berdasarkan kode_keluar
    $kelompokBarang = [];
    foreach ($barangKeluar as $item) {
        $kelompokBarang[$item['kode_keluar']][] = $item;
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Keluar</th>
                <th>Nama Barang</th>
                <th>Jumlah Keluar</th>
                <th>Tanggal Keluar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($kelompokBarang as $kodeKeluar => $items) :
                foreach ($items as $index => $item) :
            ?>
                    <tr>
                        <?php if ($index === 0) : ?>
                            <td><?= $no++; ?></td>
                            <td><?= $kodeKeluar ?></td>
                        <?php else : ?>
                            <td></td>
                            <td></td>
                        <?php endif; ?>
                        <td><?= $item['nama_barang'] ?></td>
                        <td><?= $item['jumlah_keluar'] ?></td>
                        <td><?= date('Y-m-d', strtotime($item['tanggal_keluar'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="signatures clearfix">
        <div class="signature left">
            <br><br>
            <p>(____________________)</p>
            <p>Penerima</p>
        </div>
        <div class="signature right">
            <br><br>
            <p>(____________________)</p>
            <p>Petugas Gudang</p>
        </div>
    </div>
</body>

</html>