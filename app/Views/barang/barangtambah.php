<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Tambah Barang</h4>
                <h6>Menambah List Barang</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="/barang/simpan" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" name="kode_barang" class="form-control" value="<?= $kodeBarang; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control <?= isset($validation) && $validation->hasError('nama_barang') ? 'is-invalid' : '' ?>" value="<?= old('nama_barang') ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('nama_barang') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" name="stok" class="form-control <?= isset($validation) && $validation->hasError('stok') ? 'is-invalid' : '' ?>" value="<?= old('stok') ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('stok') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control"><?= old('deskripsi') ?></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Foto Produk</label>
                                <input type="file" name="foto" class="form-control <?= isset($validation) && $validation->hasError('foto') ? 'is-invalid' : '' ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('foto') : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2">Submit</button>
                            <a href="<?= base_url('barang') ?>" class="btn btn-cancel">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>