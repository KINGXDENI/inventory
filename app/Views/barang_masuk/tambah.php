<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Tambah Barang Masuk</h4>
                <h6>Menambahkan Barang Masuk</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= base_url('barang-masuk/simpan') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="kode_masuk" class="form-label">Kode Masuk</label>
                            <input type="text" id="kode_masuk" name="kode_masuk" class="form-control" value="<?= $kodeMasuk; ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="datetime-local" id="tanggal_masuk" name="tanggal_masuk" class="form-control <?= isset($validation) && $validation->hasError('tanggal_masuk') ? 'is-invalid' : '' ?>" value="<?= old('tanggal_masuk') ?>" placeholder="Pilih tanggal masuk">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('tanggal_masuk') : '' ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-3" id="dynamic-fields-container">
                        <div class="col-md-3">
                            <label for="barang_id" class="form-label">Barang</label>
                            <select name="barang_id[]" id="barang_id" class="form-select <?= isset($validation) && $validation->hasError('barang_id.*') ? 'is-invalid' : '' ?>">
                                <option value="">Pilih Barang</option>
                                <?php foreach ($barang as $item) : ?>
                                    <option value="<?= $item['id'] ?>" <?= old('barang_id') == $item['id'] ? 'selected' : '' ?>><?= $item['nama_barang'] ?>-[<?= $item['kode_barang'] ?>]</option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('barang_id.*') : '' ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="jumlah_masuk" class="form-label">Jumlah Masuk</label>
                            <input type="number" id="jumlah_masuk" name="jumlah_masuk[]" class="form-control <?= isset($validation) && $validation->hasError('jumlah_masuk.*') ? 'is-invalid' : '' ?>" value="<?= old('jumlah_masuk') ?>" placeholder="Masukkan jumlah barang masuk">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('jumlah_masuk.*') : '' ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan[]" id="keterangan" class="form-control" placeholder="Masukkan keterangan (opsional)"><?= old('keterangan') ?></textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success" id="btn-tambah-lainnya">Tambah Lainnya</button>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="<?= base_url('barang-masuk') ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnTambahLainnya = document.getElementById('btn-tambah-lainnya');
        const container = document.getElementById('dynamic-fields-container');
        let index = 2; // Dimulai dari 2 karena sudah ada satu form statis

        btnTambahLainnya.addEventListener('click', function() {
            const newFieldSet = document.createElement('div');
            newFieldSet.classList.add('row', 'g-3', 'mt-3');
            newFieldSet.id = `row${index}`;
            newFieldSet.innerHTML = `
                <div class="col-md-3">
                    <label for="barang_id" class="form-label">Barang</label>
                    <select name="barang_id[]" id="barang_id" class="form-select <?= isset($validation) && $validation->hasError('barang_id.*') ? 'is-invalid' : '' ?>">
                        <option value="">Pilih Barang</option>
                        <?php foreach ($barang as $item) : ?>
                            <option value="<?= $item['id'] ?>" <?= old('barang_id') == $item['id'] ? 'selected' : '' ?>><?= $item['nama_barang'] ?>-[<?= $item['kode_barang'] ?>]</option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= isset($validation) ? $validation->getError('barang_id.*') : '' ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="jumlah_masuk" class="form-label">Jumlah Masuk</label>
                    <input type="number" id="jumlah_masuk" name="jumlah_masuk[]" class="form-control <?= isset($validation) && $validation->hasError('jumlah_masuk.*') ? 'is-invalid' : '' ?>" value="<?= old('jumlah_masuk') ?>" placeholder="Masukkan jumlah barang masuk">
                    <div class="invalid-feedback">
                        <?= isset($validation) ? $validation->getError('jumlah_masuk.*') : '' ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan[]" id="keterangan" class="form-control" placeholder="Masukkan keterangan (opsional)"><?= old('keterangan') ?></textarea>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger mt-3 btn-hapus-lainnya" data-row-id="${index}">Hapus</button>
                </div>
            `;
            container.appendChild(newFieldSet);
            index++;
        });

        // Script untuk menghapus field dinamis
        container.addEventListener('click', function(event) {
            if (event.target.classList.contains('btn-hapus-lainnya')) {
                const rowId = event.target.dataset.rowId;
                const rowToRemove = document.getElementById(`row${rowId}`);
                rowToRemove.remove();
            }
        });
    });
</script>
<?= $this->endSection('content'); ?>