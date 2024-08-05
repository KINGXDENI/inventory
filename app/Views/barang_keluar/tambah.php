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

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="kode_keluar" class="form-label">Kode Keluar</label>
                            <input type="text" id="kode_keluar" name="kode_keluar" class="form-control" value="<?= $kodeKeluar ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                            <input type="datetime-local" id="tanggal_keluar" name="tanggal_keluar" class="form-control <?= isset($validation) && $validation->hasError('tanggal_keluar') ? 'is-invalid' : '' ?>" value="<?= old('tanggal_keluar') ?>" placeholder="Pilih tanggal keluar">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('tanggal_keluar') : '' ?>
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
                            <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                            <input type="number" id="jumlah_keluar" name="jumlah_keluar[]" class="form-control <?= isset($validation) && $validation->hasError('jumlah_keluar.*') ? 'is-invalid' : '' ?>" value="<?= old('jumlah_keluar') ?>" placeholder="Masukkan jumlah barang keluar" min="1">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('jumlah_keluar.*') : '' ?>
                            </div>
                        </div>
                        <div class="col-md-3">
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
                            <a href="<?= base_url('barang-keluar') ?>" class="btn btn-secondary">Batal</a>
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
                            <option value="<?= $item['id'] ?>" <?= old('barang_id') == $item['id'] ? 'selected' : '' ?>><?= $item['nama_barang'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= isset($validation) ? $validation->getError('barang_id.*') : '' ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                    <input type="number" id="jumlah_keluar" name="jumlah_keluar[]" class="form-control <?= isset($validation) && $validation->hasError('jumlah_keluar.*') ? 'is-invalid' : '' ?>" value="<?= old('jumlah_keluar') ?>" placeholder="Masukkan jumlah barang keluar">
                    <div class="invalid-feedback">
                        <?= isset($validation) ? $validation->getError('jumlah_keluar.*') : '' ?>
                    </div>
                </div>
                <div class="col-md-3">
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