<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Laporan Barang Keluar</h4>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= base_url('laporan-barang-keluar/generate') ?>">
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
                                <th>Total Barang Keluar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($laporanBarangKeluar as $laporan) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $laporan['periode_awal'] ?></td>
                                    <td><?= $laporan['periode_akhir'] ?></td>
                                    <td><?= $laporan['total_barang_keluar'] ?></td>
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