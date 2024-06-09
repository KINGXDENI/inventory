<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Barang Keluar</h4>
                <h6>Daftar Barang Keluar</h6>
            </div>
            <div class="page-btn">
                <a href="<?= base_url('barang-keluar/tambah') ?>" class="btn btn-added"><img src="<?= base_url(); ?>/assets/img/icons/plus.svg" alt="img" class="me-1">Tambah Barang Keluar</a>
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
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <img src="<?= base_url(); ?>/assets/img/icons/filter.svg" alt="img">
                                <span><img src="<?= base_url(); ?>/assets/img/icons/closes.svg" alt="img"></span>
                            </a>
                        </div>
                        <div class="search-input">
                            <a class="btn btn-searchset"><img src="<?= base_url(); ?>/assets/img/icons/search-white.svg" alt="img"></a>
                        </div>
                    </div>
                    <div class="wordset">
                        <ul>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="<?= base_url(); ?>/assets/img/icons/pdf.svg" alt="img"></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="<?= base_url(); ?>/assets/img/icons/excel.svg" alt="img"></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="<?= base_url(); ?>/assets/img/icons/printer.svg" alt="img"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Keluar</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Keluar</th>
                                <th>Tanggal Keluar</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($barangKeluar as $item) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $item['kode_keluar'] ?></td>
                                    <td><?= $item['nama_barang'] ?></td>
                                    <td><?= $item['jumlah_keluar'] ?></td>
                                    <td><?= $item['tanggal_keluar'] ?></td>
                                    <td><?= $item['keterangan'] ?></td>
                                    <td>
                                        <a class="me-3" href="<?= base_url('barang-keluar/edit/' . $item['id']) ?>">
                                            <img src="<?= base_url(); ?>/assets/img/icons/edit.svg" alt="img">
                                        </a>
                                        <a class="delete-barang-keluar" href="javascript:void(0);" data-id="<?= $item['id'] ?>" data-barang-id="<?= $item['barang_id'] ?>" data-jumlah-keluar="<?= $item['jumlah_keluar'] ?>">
                                            <img src="<?= base_url(); ?>/assets/img/icons/delete.svg" alt="img">
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
<script>
    const deleteButtons = document.querySelectorAll('.delete-barang-keluar');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const barangId = this.dataset.id;

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data barang akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('barang-keluar/hapus/') ?>" + barangId;
                }
            });
        });
    });
</script>
<?= $this->endSection('content'); ?>