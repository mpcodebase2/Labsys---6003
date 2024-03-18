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
</head>

<body>
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

<!-- page-wrapper Start-->
<div class="page-wrapper compact-wrapper" id="pageWrapper">

    <!-- Header Start -->
    <?= $this->include('layout/header') ?>
    <!--Header End  -->

    <!-- Page Body Start-->
    <div class="page-body-wrapper">

        <!-- Page Sidebar Start-->
        <?= $this->include('layout/sidebar') ?>
        <!-- Page Sidebar Ends-->

        <div class="page-body">
            <!-- Main Content Start -->
            <?= $this->renderSection('main-content') ?>
            <!-- Main Content End -->
        </div>

        <!-- Footer -->
        <?= $this->include('layout/footer') ?>
    </div>
</div>
<!-- loader starts-->
<div class="loader-wrapper">
    <div class="loader-index"><span></span></div>
    <svg>
        <defs></defs>
        <filter id="goo">
            <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
            <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo"> </fecolormatrix>
        </filter>
    </svg>
</div>
<!-- loader ends-->
<!-- Script-->
<?= $this->include('layout/script') ?>
</body>

</html>