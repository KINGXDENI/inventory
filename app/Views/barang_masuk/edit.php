<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Edit Barang Masuk</h4>
                <h6>Mengubah Data Barang Masuk</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= base_url('barang-masuk/update/' . $barangMasuk['id']) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Barang</label>
                                <select name="barang_id" class="form-control <?= isset($validation) && $validation->hasError('barang_id') ? 'is-invalid' : '' ?>">
                                    <option value="">Pilih Barang</option>
                                    <?php foreach ($barang as $item) : ?>
                                        <option value="<?= $item['id'] ?>" <?= ($item['id'] == $barangMasuk['barang_id']) ? 'selected' : '' ?>><?= $item['nama_barang'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('barang_id') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Jumlah Masuk</label>
                                <input type="number" name="jumlah_masuk" class="form-control <?= isset($validation) && $validation->hasError('jumlah_masuk') ? 'is-invalid' : '' ?>" value="<?= old('jumlah_masuk', $barangMasuk['jumlah_masuk']) ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('jumlah_masuk') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Tanggal Masuk</label>
                                <input type="datetime-local" name="tanggal_masuk" class="form-control <?= isset($validation) && $validation->hasError('tanggal_masuk') ? 'is-invalid' : '' ?>" value="<?= old('tanggal_masuk', $barangMasuk['tanggal_masuk']) ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('tanggal_masuk') : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2">Simpan Perubahan</button>
                            <a href="<?= base_url('barang-masuk') ?>" class="btn btn-cancel">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>