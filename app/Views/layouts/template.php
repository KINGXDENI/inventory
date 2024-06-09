<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Invetory WEB">
    <meta name="robots" content="noindex, nofollow">
    <title><?= $title; ?></title>

    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>/assets/img/favicon.png">

    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/animate.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/style.css">
    <script src="<?= base_url(); ?>/assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
</head>

<body>
    <!-- <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div> -->

    <div class="main-wrapper">

        <div class="header">

            <div class="header-left active pt-2">
                <a href="/" class="logo">
                    <img src="<?= base_url(); ?>/assets/img/logo.png" alt="">
                </a>
                <a href="/" class="logo-small">
                    <img src="<?= base_url(); ?>/assets/img/logo-small.png" alt="">
                </a>
                <a id="toggle_btn" href="javascript:void(0);">
                </a>
            </div>

            <a id="mobile_btn" class="mobile_btn" href="#sidebar">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>

            <ul class="nav user-menu">

                <li class="nav-item">
                    <div class="top-nav-search">
                        <a href="javascript:void(0);" class="responsive-search">
                            <i class="fa fa-search"></i>
                        </a>
                        <form action="#">
                            <div class="searchinputs">
                                <input type="text" placeholder="Search Here ...">
                                <div class="search-addon">
                                    <span><img src="<?= base_url(); ?>/assets/img/icons/closes.svg" alt="img"></span>
                                </div>
                            </div>
                            <a class="btn" id="searchdiv"><img src="<?= base_url(); ?>/assets/img/icons/search.svg" alt="img"></a>
                        </form>
                    </div>
                </li>

                <li class="nav-item dropdown has-arrow main-drop">
                    <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                        <span class="user-img"><img src="<?= base_url(); ?>/assets/img/profiles/avator1.jpg" alt="">
                            <span class="status online"></span></span>
                    </a>
                    <div class="dropdown-menu menu-drop-user">
                        <div class="profilename">
                            <div class="profileset">
                                <span class="user-img"><img src="<?= base_url(); ?>/assets/img/profiles/avator1.jpg" alt="">
                                    <span class="status online"></span></span>
                                <div class="profilesets">
                                    <h6>John Doe</h6>
                                    <h5>Admin</h5>
                                </div>
                            </div>
                            <hr class="m-0">
                            <a class="dropdown-item" href="profile.html"> <i class="me-2" data-feather="user"></i> My
                                Profile</a>
                            <a class="dropdown-item logout pb-0" href="signin.html"><img src="<?= base_url(); ?>/assets/img/icons/log-out.svg" class="me-2" alt="img">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>


            <div class="dropdown mobile-user-menu">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="profile.html">My Profile</a>
                    <a class="dropdown-item" href="signin.html">Logout</a>
                </div>
            </div>

        </div>
        <?= $this->include('layouts/sidebar'); ?>

        <?= $this->renderSection('content'); ?>


        <script src="<?= base_url(); ?>/assets/js/jquery-3.6.0.min.js"></script>

        <script src="<?= base_url(); ?>/assets/js/feather.min.js"></script>

        <script src="<?= base_url(); ?>/assets/js/jquery.slimscroll.min.js"></script>

        <script src="<?= base_url(); ?>/assets/js/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>/assets/js/dataTables.bootstrap4.min.js"></script>

        <script src="<?= base_url(); ?>/assets/js/bootstrap.bundle.min.js"></script>

        <script src="<?= base_url(); ?>/assets/plugins/apexchart/apexcharts.min.js"></script>
        <script src="<?= base_url(); ?>/assets/plugins/apexchart/chart-data.js"></script>
        <script src="<?= base_url(); ?>/assets/plugins/select2/js/select2.min.js"></script>
        <script src="<?= base_url(); ?>/assets/js/script.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const currentUrl = window.location.pathname;
                const menuItems = document.querySelectorAll('.sidebar-menu ul li a');

                menuItems.forEach(item => {
                    const href = item.getAttribute('href');

                    // Periksa apakah URL saat ini sama persis dengan href item menu, atau apakah dimulai dengan href item menu (kecuali jika href adalah "/")
                    if (currentUrl === href || (currentUrl.startsWith(href) && href !== '/')) {
                        item.parentElement.classList.add('active');
                    }
                });
            });
        </script>
</body>

</html>