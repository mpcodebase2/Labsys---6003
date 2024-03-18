<?= $this->extend('layout/landing_layout') ?>

<?= $this->section('main-content') ?>
<style>
    .landing-home .sticky-header header .nav-padding {
        padding: 5px 50px !important;
    }
    .landing-page .sticky-header header {
        background: #000248 !important;
    }
    .landing-page {
        background-image: url(<?=base_url()?>assets/images/home-bg.jpeg);
        background-size: cover;
        background-repeat: no-repeat;
    }
</style>
    <div class="container-fluid">
        <div class="row widget-grid">
            <div class="col-sm-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 style="margin-top: 25%; font-size: 65px;">Welcome to</br>ABC Lab</h2>

                        <div class="row" style="width: 400px; margin: 5% auto;">
                            <?php if(!session('user_id')){ ?>
                            <div class="col-6">
                                <a href="<?=base_url()?>sign-up" class="btn btn-pill btn-primary btn-lg">SignUp</a>
                            </div>
                            <div class="col-6">
                                <a href="<?=base_url()?>login" class="btn btn-pill btn-success btn-lg">SignIn</a>
                            </div>
                            <?php }else{?>
                            <div class="row" style="width: 400px; margin: 5% auto;">
                                <div class="col-12">
                                    <a href="<?=base_url()?>dashboard" class="btn btn-pill btn-success btn-lg">Dashboard</a>
                                </div>
                            </div>
                            <?php }?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>