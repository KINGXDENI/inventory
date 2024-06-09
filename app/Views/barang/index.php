<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title m-auto">
                <h1>SISTEM INVENTORY BARANG</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count">
                    <div class="dash-counts">
                        <h4><?= $jumlahBarang; ?></h4>
                        <h5>Barang</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="package"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das1">
                    <div class="dash-counts">
                        <h4><?= $jumlahBarangMasuk; ?></h4>
                        <h5>Barang Masuk</h5>
                    </div>
                    <div class="dash-imgs">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das2">
                    <div class="dash-counts">
                        <h4><?= $jumlahBarangKeluar; ?></h4>
                        <h5>Barang Keluar</h5>
                    </div>
                    <div class="dash-imgs">
                        <i class="fas fa-chevron-up"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das3">
                    <div class="dash-counts">
                        <h4>105</h4>
                        <h5>User</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="user"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>