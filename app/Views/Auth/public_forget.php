<?= $this->extend('layout/auth_layout') ?>

<?= $this->section('main-content') ?>

    <!-- login page start-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-5"><img class="bg-img-cover bg-center" src="<?=base_url()?>/assets/images/login/3.jpg" alt="looginpage"></div>
            <div class="col-xl-7 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div><a class="logo text-start" href="index.html"><img class="img-fluid for-light" src="<?=base_url()?>/assets/images/logo/logo.png" alt="looginpage"><img class="img-fluid for-dark" src="<?=base_url()?>/assets/images/logo/logo_dark.png" alt="looginpage"></a></div>
                        <div class="login-main">
                            <form class="theme-form" method="post" action="">
                                <h4>Reset your password</h4>
                                <p>Enter your email to get link</p>
                                <?php if(session()->has('error')): ?>
                                    <div class="alert alert-light-secondary" role="alert">
                                        <p class="txt-secondary"><strong>Error!</strong> <?= session('error') ?></p>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label class="col-form-label">Email Address</label>
                                    <input class="form-control" type="email" name="email" required="" placeholder="Test@gmail.com">
                                </div>
                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block w-100" type="submit">Reset</button>
                                </div>
                                <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2" href="<?=base_url()?>login">Sign in</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>