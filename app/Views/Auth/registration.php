<?= $this->extend('layout/auth_layout') ?>

<?= $this->section('main-content') ?>
    <!-- login page start-->
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-xl-5"><img class="bg-img-cover bg-center" src="<?=base_url()?>/assets/images/login/3.jpg" alt="looginpage"></div>
            <div class="col-xl-7 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div><a class="logo text-start" href="<?=base_url()?>"><img class="img-fluid for-light" src="<?=base_url()?>/assets/images/logo/logo.png" alt="looginpage"><img class="img-fluid for-dark" src="../assets/images/logo/logo_dark.png" alt="looginpage"></a></div>
                        <div class="login-main">
                            <form class="theme-form" method="post" action="<?=base_url()?>regsiter" form>
                                <h4>Create your account</h4>
                                <?php if(session()->has('error')): ?>
                                    <div class="alert alert-light-secondary" role="alert">
                                        <p class="txt-secondary"><strong>Error!</strong> <?= session('error') ?></p>
                                    </div>
                                <?php endif; ?>
                                <p>Enter your personal details to create account</p>
                                <div class="form-group">
                                    <label class="col-form-label pt-0">Your Name</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input class="form-control" type="text" required="" placeholder="First name">
                                        </div>
                                        <div class="col-6">
                                            <input class="form-control" type="text" required="" placeholder="Last name">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Email Address</label>
                                    <input class="form-control" type="email" required="" placeholder="Test@gmail.com">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Phone Number</label>
                                    <input class="form-control" type="number" required="" placeholder="0777123456">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Password</label>
                                    <div class="form-input position-relative">
                                        <input class="form-control" type="password" name="login[password]" required="" placeholder="*********">
                                        <div class="show-hide"><span class="show"></span></div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <div class="checkbox p-0">
                                        <input id="checkbox1" type="checkbox">
                                        <label class="text-muted" for="checkbox1">Agree with<a class="ms-2" href="#">Privacy Policy</a></label>
                                    </div>
                                    <button class="btn btn-primary btn-block w-100" type="submit">Create Account</button>
                                </div>
                                <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2" href="<?=base_url()?>admin/login">Sign in</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>