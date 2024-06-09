<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Laporan Barang Masuk</h4>
            </div>
        </div>
        <?php if (session()->has('success')) : ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '<?= session('success') ?>',
                    timer: 1000,
                }).then(() => {
                    <?php session()->remove('success'); ?> // Hapus pesan sukses setelah ditampilkan
                });
            </script>
        <?php endif; ?>

        <?php if (session()->has('error')) : ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '<?= session('error') ?>',
                    timer: 1000,
                }).then(() => {
                    <?php session()->remove('error'); ?> // Hapus pesan error setelah ditampilkan
                });
            </script>
        <?php endif; ?>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= base_url('laporan-barang-masuk/generate') ?>">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Periode Awal</label>
                                <input type="date" name="periode_awal" class="form-control" value="<?= old('periode_awal') ?>">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Periode Akhir</label>
                                <input type="date" name="periode_akhir" class="form-control" value="<?= old('periode_akhir') ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">Generate</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive mt-4">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Periode Awal</th>
                                <th>Periode Akhir</th>
                                <th>Total Barang Masuk</th>
                                <th>Detail Barang Masuk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($laporanBarangMasuk as $laporan) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $laporan['periode_awal'] ?></td>
                                    <td><?= $laporan['periode_akhir'] ?></td>
                                    <td><?= $laporan['total_barang_masuk'] ?></td>
                                    <td>
                                        <?php foreach ($barangMasuk as $item) : ?>
                                            <?php if ($item['tanggal_masuk'] >= $laporan['periode_awal'] && $item['tanggal_masuk'] <= $laporan['periode_akhir']) : ?>
                                                <p><?= $item['nama_barang'] ?> (<?= $item['jumlah_masuk'] ?>)</p>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td class="d-flex">
                                        <a href="<?= base_url('laporan-barang-masuk/export/pdf?periode_awal=' . $laporan['periode_awal'] . '&periode_akhir=' . $laporan['periode_akhir']) ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="PDF">
                                            <img src="<?= base_url(); ?>/assets/img/icons/pdf.svg" alt="img">
                                        </a>
                                        <a href="<?= base_url('laporan-barang-masuk/export/excel?periode_awal=' . $laporan['periode_awal'] . '&periode_akhir=' . $laporan['periode_akhir']) ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Excel">
                                            <img src="<?= base_url(); ?>/assets/img/icons/excel.svg" alt="img">
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>