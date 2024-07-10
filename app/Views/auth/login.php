<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Invetory WEB">
    <meta name="robots" content="noindex, nofollow">
    <title><?= $title; ?></title>

    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>/assets/img/favicon1.png">

    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/style.css">
    <script src="<?= base_url(); ?>/assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
</head>

<body class="account-page">
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
    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <div class="login-userset">
                        <div class="login-logo">
                            <img src="<?= base_url(); ?>assets/img/logo1.png" alt="img">
                        </div>
                        <div class="login-userheading">
                            <h2><?= $title; ?></h2>
                        </div>

                        <form method="POST" action="<?= base_url('loginProcess'); ?>">
                            <?= csrf_field() ?>

                            <div class="form-login">
                                <label>Email</label>
                                <div class="form-addons">
                                    <input type="email" name="email" placeholder="Enter your email address" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : ''; ?>" value="<?= old('email'); ?>">
                                    <img src="<?= base_url(); ?>assets/img/icons/mail.svg" alt="img">
                                    <?php if (isset($validation) && $validation->hasError('email')) : ?>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('email'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-login">
                                <label>Password</label>
                                <div class="pass-group">
                                    <input type="password" name="password" class="pass-input form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : ''; ?>" placeholder="Enter your password">
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                    <?php if (isset($validation) && $validation->hasError('password')) : ?>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('password'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if (session()->has('error')) : ?>
                                <div class="alert alert-danger">
                                    <?= session('error'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-login">
                                <div class="alreadyuser">
                                    <h4><a href="forgetpassword.html" class="hover-a">Forgot Password?</a></h4>
                                </div>
                            </div>

                            <div class="form-login">
                                <button type="submit" class="btn btn-login">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="login-img">
                    <img src="<?= base_url(); ?>assets/img/login.jpg" alt="img">
                </div>
            </div>
        </div>
    </div>


    <script src="<?= base_url(); ?>/assets/js/jquery-3.6.0.min.js"></script>

    <script src="<?= base_url(); ?>/assets/js/feather.min.js"></script>

    <script src="<?= base_url(); ?>/assets/js/bootstrap.bundle.min.js"></script>

    <script src="<?= base_url(); ?>/assets/js/script.js"></script>
</body>

</html>