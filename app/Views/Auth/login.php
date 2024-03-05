<?= $this->extend('layout/auth_layout') ?>

<?= $this->section('main-content') ?>
<!-- login page start-->
<div class="container-fluid p-0">
    <div class="row m-0">
        <div class="col-12 p-0">
            <div class="login-card login-dark">
                <div>
                    <div><a class="logo" href="<?=base_url()?>"><img class="img-fluid for-light" src="<?=base_url()?>/assets/images/logo/logo.png" alt="looginpage"><img class="img-fluid for-dark" src="../assets/images/logo/logo_dark.png" alt="looginpage"></a></div>
                    <div class="login-main">
                        <form class="theme-form" method="post" action="<?=base_url()?>admin/api/login">
                            <h4>Sign in to account</h4>
                            <p>Enter your email & password to login</p>
                            <?php if(session()->has('error')): ?>
                                <div class="alert alert-light-secondary" role="alert">
                                    <p class="txt-secondary"><strong>Error!</strong> <?= session('error') ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label class="col-form-label">Username</label>
                                <input class="form-control" type="text" required="" placeholder="Username" name="username">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Password</label>
                                <div class="form-input position-relative">
                                    <input class="form-control" type="password" name="password" required="" placeholder="*********">

                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="checkbox p-0">
                                    <input id="checkbox1" type="checkbox">
                                    <label class="text-muted" for="checkbox1">Remember password</label>
                                </div><a class="link" href="">Forgot password?</a>
                                <div class="text-end mt-3">
                                    <button class="btn btn-primary btn-block w-100" type="submit">Sign in</button>
                                </div>
                            </div>
                            <p class="mt-4 mb-0 text-center">Don't have account?<a class="ms-2" href="<?=base_url()?>admin/sign-up">Create Account</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>