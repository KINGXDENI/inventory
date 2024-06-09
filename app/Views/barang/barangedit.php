<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Edit Barang</h4>
                <h6>Mengubah Data Barang</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= base_url('barang/update/' . $barang['id']) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="fotoLama" value="<?= $barang['foto'] ?>">

                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" name="kode_barang" class="form-control <?= isset($validation) && $validation->hasError('kode_barang') ? 'is-invalid' : '' ?>" value="<?= old('kode_barang', $barang['kode_barang']) ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('kode_barang') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control <?= isset($validation) && $validation->hasError('nama_barang') ? 'is-invalid' : '' ?>" value="<?= old('nama_barang', $barang['nama_barang']) ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('nama_barang') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" name="stok" class="form-control <?= isset($validation) && $validation->hasError('stok') ? 'is-invalid' : '' ?>" value="<?= old('stok', $barang['stok']) ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('stok') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control"><?= old('deskripsi', $barang['deskripsi']) ?></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Foto Produk</label>
                                <input type="file" name="foto" class="form-control <?= isset($validation) && $validation->hasError('foto') ? 'is-invalid' : '' ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('foto') : '' ?>
                                </div>

                                <?php if ($barang['foto']) : ?>
                                    <div class="mt-2">
                                        <img src="<?= base_url('barangimg/' . $barang['foto']) ?>" alt="Foto Barang Lama" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2">Simpan Perubahan</button>
                            <a href="<?= base_url('barang') ?>" class="btn btn-cancel">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>