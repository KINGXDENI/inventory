<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="<?= current_url() == base_url() ? 'active' : '' ?>">
                    <a href="/"><img src="<?= base_url('assets/img/icons/dashboard.svg') ?>" alt="img"><span> Dashboard</span> </a>
                </li>
                <li class="<?= current_url() == base_url('barang') ? 'active' : '' ?>">
                    <a href="/barang"><img src="<?= base_url('assets/img/icons/product.svg') ?>" alt="img"><span> Daftar Barang</span> </a>
                </li>
                <li class="<?= current_url() == base_url('barang') ? 'active' : '' ?>">
                    <a href="/barang-masuk"><img src="<?= base_url('assets/img/icons/product.svg') ?>" alt="img"><span> Daftar Barang</span> </a>
                </li>
            </ul>
        </div>
    </div>
</div>