<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Tambah Barang Keluar</h4>
                <h6>Menambahkan Barang Keluar</h6>
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
                <form method="POST" action="<?= base_url('barang-keluar/simpan') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Kode Keluar</label>
                                <input type="text" name="kode_keluar" class="form-control <?= isset($validation) && $validation->hasError('kode_keluar') ? 'is-invalid' : '' ?>" value="<?= old('kode_keluar') ?>" placeholder="Masukkan kode keluar">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('kode_keluar') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Barang</label>
                                <select name="barang_id" class="form-control <?= isset($validation) && $validation->hasError('barang_id') ? 'is-invalid' : '' ?>">
                                    <option value="">Pilih Barang</option>
                                    <?php foreach ($barang as $item) : ?>
                                        <option value="<?= $item['id'] ?>" <?= old('barang_id') == $item['id'] ? 'selected' : '' ?>><?= $item['nama_barang'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('barang_id') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Jumlah Keluar</label>
                                <input type="number" name="jumlah_keluar" class="form-control <?= isset($validation) && $validation->hasError('jumlah_keluar') ? 'is-invalid' : '' ?>" value="<?= old('jumlah_keluar') ?>" placeholder="Masukkan jumlah barang keluar" min="1">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('jumlah_keluar') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Tanggal Keluar</label>
                                <input type="datetime-local" name="tanggal_keluar" class="form-control <?= isset($validation) && $validation->hasError('tanggal_keluar') ? 'is-invalid' : '' ?>" value="<?= old('tanggal_keluar') ?>" placeholder="Pilih tanggal dan waktu keluar">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('tanggal_keluar') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" placeholder="Masukkan keterangan (opsional)"><?= old('keterangan') ?></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2">Simpan</button>
                            <a href="<?= base_url('barang-keluar') ?>" class="btn btn-cancel">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>