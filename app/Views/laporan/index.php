<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Laporan Umum</h4>
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
                <form method="POST" action="<?= base_url('laporan/generate') ?>">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Laporan</label>
                                <select name="jenis_laporan" class="form-control <?= isset($validation) && $validation->hasError('jenis_laporan') ? 'is-invalid' : '' ?>">
                                    <option value="" disabled selected>Pilih Jenis Laporan</option>
                                    <option value="masuk" <?= (old('jenis_laporan', $jenisLaporan ?? '') == 'masuk') ? 'selected' : '' ?>>Barang Masuk</option>
                                    <option value="keluar" <?= (old('jenis_laporan', $jenisLaporan ?? '') == 'keluar') ? 'selected' : '' ?>>Barang Keluar</option>
                                </select>
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('jenis_laporan') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <select name="barang_id" class="form-control">
                                    <option value="">Semua Barang</option>
                                    <?php foreach ($barang as $item) : ?>
                                        <option value="<?= $item['id'] ?>" <?= old('barang_id', $barangId ?? '') == $item['id'] ? 'selected' : '' ?>><?= $item['nama_barang'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Periode Awal</label>
                                <input type="date" name="periode_awal" class="form-control <?= isset($validation) && $validation->hasError('periode_awal') ? 'is-invalid' : '' ?>" value="<?= old('periode_awal', $periodeAwal ?? '') ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('periode_awal') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Periode Akhir</label>
                                <input type="date" name="periode_akhir" class="form-control <?= isset($validation) && $validation->hasError('periode_akhir') ? 'is-invalid' : '' ?>" value="<?= old('periode_akhir', $periodeAkhir ?? '') ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('periode_akhir') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3 text-center">
                            <button type="submit" class="btn btn-primary btn-block">Generate</button>
                        </div>
                    </div>
                </form>

                <?php if (!empty($laporan)) : ?>
                    <div class="table-top mt-3">
                        <div class="search-set">
                            <div class="search-input">
                                <a class="btn btn-searchset"><img src="<?= base_url(); ?>/assets/img/icons/search-white.svg" alt="img"></a>
                            </div>
                        </div>
                        <div class="wordset">
                            <ul>
                                <li>
                                    <a href="<?= base_url('laporan/export/pdf?jenis_laporan=' . $jenisLaporan . '&periode_awal=' . $periodeAwal . '&periode_akhir=' . $periodeAkhir) ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="PDF">
                                        <img src="<?= base_url(); ?>/assets/img/icons/pdf.svg" alt="img">
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url('laporan/export/excel?jenis_laporan=' . $jenisLaporan . '&periode_awal=' . $periodeAwal . '&periode_akhir=' . $periodeAkhir) ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Excel">
                                        <img src="<?= base_url(); ?>/assets/img/icons/excel.svg" alt="img">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th><?= ($jenisLaporan == 'masuk') ? 'Jumlah Masuk' : 'Jumlah Keluar' ?></th>
                                    <th>Tanggal <?= ($jenisLaporan == 'masuk') ? 'Masuk' : 'Keluar' ?></th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($laporan as $item) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $item['nama_barang'] ?></td>
                                        <td><?= $item[$jenisLaporan == 'masuk' ? 'jumlah_masuk' : 'jumlah_keluar'] ?></td>
                                        <td><?= $item[$jenisLaporan == 'masuk' ? 'tanggal_masuk' : 'tanggal_keluar'] ?></td>
                                        <td><?= $item['keterangan'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>