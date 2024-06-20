<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <?php if (session()->get('pengguna')['jabatan'] == 'admin') : ?>
                    <li>
                        <a href="/"><i data-feather="home"></i><span> Dashboard</span></a>
                    </li>
                    <li>
                        <a href="/barang"><img src="<?= base_url('assets/img/icons/product.svg') ?>" alt="img"><span> Daftar Barang</span></a>
                    </li>
                    <li>
                        <a href="/barang-masuk"><i class="fas fa-box"></i><span> Daftar Barang Masuk</span></a>
                    </li>
                    <li>
                        <a href="/barang-keluar"><i class="fas fa-box-open"></i><span> Daftar Barang Keluar</span></a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="/laporan"><i data-feather="printer"></i><span> Laporan Barang</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>