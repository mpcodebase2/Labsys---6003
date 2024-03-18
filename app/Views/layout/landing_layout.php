<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ABC Laboratories – Lab Appointment System">
    <meta name="keywords" content="">
    <meta name="author" content="ABC Laboratories">
    <link rel="icon" href="<?=base_url()?>/assets/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?=base_url()?>/assets/images/favicon.png" type="image/x-icon">
    <title><?=(isset($title))?:''?> - ABC Laboratories | Lab Appointment System</title>
    <!-- latest jquery-->
    <script src="<?= base_url() ?>assets/js/jquery-3.5.1.min.js"></script>
    <script src="<?= base_url() ?>assets/js/autofill.js"></script>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    <!-- css -->
    <?= $this->include('layout/css') ?>
    <script>
        let baseUrl = '<?= base_url()?>';
        let tk = '<?= csrf_token() ?>';
    </script>
    <style>
        .landing-page .sticky-header header {
            background: #3a3a3a !important;
        }
        section.first-section {
            margin-top: 26px;
        }
        form {
            text-align: left;
        }
    </style>
</head>
<body class="landing-page">
<!-- loader starts-->
<div class="loader-wrapper">
    <div class="loader-index"><span></span></div>
    <svg enable-background="new 0 0 0 0" version="1.1" viewBox="0 0 100 100" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
  <circle cx="6" cy="50" r="6" fill="#fff">
      <animateTransform attributeName="transform" begin="0.1" dur="1s" repeatCount="indefinite" type="translate" values="0 15 ; 0 -15; 0 15"/>
  </circle>
        <circle cx="30" cy="50" r="6" fill="#fff">
            <animateTransform attributeName="transform" begin="0.2" dur="1s" repeatCount="indefinite" type="translate" values="0 10 ; 0 -10; 0 10"/>
        </circle>
        <circle cx="54" cy="50" r="6" fill="#fff">
            <animateTransform attributeName="transform" begin="0.3" dur="1s" repeatCount="indefinite" type="translate" values="0 5 ; 0 -5; 0 5"/>
        </circle>
</svg>
</div>
<!-- loader ends-->
<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- tap on tap ends-->
<!-- page-wrapper Start-->
<div class="landing-page">
    <!-- Page Body Start            -->
    <div class="landing-home">
        <div class="container-fluid">
            <div class="sticky-header">
                <header>
                    <nav class="navbar navbar-b navbar-dark navbar-trans navbar-expand-xl fixed-top nav-padding" id="sidebar-menu"><a class="navbar-brand p-0" href="#"><img class="img-fluid" src="<?= base_url() ?>/assets/images/logo/logo.png" alt=""></a>
                        <button class="navbar-toggler navabr_btn-set custom_nav" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation"><span></span><span></span><span></span></button>
                        <div class="navbar-collapse justify-content-center collapse hidenav" id="navbarDefault">
                            <ul class="navbar-nav navbar_nav_modify" id="scroll-spy">
                                <li class="nav-item"><a class="nav-link" href="<?=base_url()?>">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?=base_url()?>dashboard">Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="#">Contact US</a></li>
                            </ul>
                        </div>
                        <div class="buy-btn rounded-pill">
                            <a class="nav-link js-scroll" href="<?=base_url()?>create-appointment" target="_self">MAKE AN APPOINTMENT</a>
                        </div>
                    </nav>
                </header>
            </div>
        </div>
    </div>
    <section class="section-space premium-wrap first-section">
        <div class="container">
            <div class="page-body">
                <!-- Main Content Start -->
                <?= $this->renderSection('main-content') ?>
                <!-- Main Content End -->
            </div>
        </div>
    </section>


</div>
<!-- Bootstrap js-->
<script src="<?= base_url() ?>/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
<!-- feather icon js-->
<script src="<?= base_url() ?>/assets/js/icons/feather-icon/feather.min.js"></script>
<script src="<?= base_url() ?>/assets/js/icons/feather-icon/feather-icon.js"></script>
<!-- scrollbar js-->
<script src="<?= base_url() ?>/assets/js/scrollbar/simplebar.js"></script>
<script src="<?= base_url() ?>/assets/js/scrollbar/custom.js"></script>
<!-- Sidebar jquery-->
<script src="<?= base_url() ?>/assets/js/config.js"></script>
<link href="<?php echo base_url(); ?>assets/toastr/toastr.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/toastr/toastr.min.js"></script>
<!-- include script & Plugins -->
<?= $this->renderSection('script') ?>

<!-- Theme js-->
<script src="<?= base_url() ?>/assets/js/script.js"></script>
<!-- login js-->
</body>
</html>