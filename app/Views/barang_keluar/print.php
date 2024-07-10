<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?></title>
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
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .footer {
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <h2>Nota Barang Keluar</h2>
    <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Keluar</th>
                <th>Nama Barang</th>
                <th>Jumlah Keluar</th>
                <th>Tanggal Keluar</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php $printedKodeKeluar = []; ?>
            <?php foreach ($barangKeluar as $item) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <?php if (!in_array($item['kode_keluar'], $printedKodeKeluar)) : ?>
                        <td rowspan="<?= count(array_filter($barangKeluar, function ($el) use ($item) {
                                            return $el['kode_keluar'] == $item['kode_keluar'];
                                        })) ?>">
                            <?= $item['kode_keluar'] ?>
                        </td>
                        <?php $printedKodeKeluar[] = $item['kode_keluar']; ?>
                    <?php endif; ?>
                    <td><?= $item['nama_barang'] ?></td>
                    <td><?= $item['jumlah_keluar'] ?></td>
                    <td><?= $item['tanggal_keluar'] ?></td>
                    <td><?= $item['keterangan'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Sistem Inventory Barang</p>
    </div>
</body>

</html>