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
    <!-- Google font-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- css -->
    <script>
        let baseUrl = '<?= base_url()?>';
        let tk = '<?= csrf_token() ?>';
    </script>
</head>

<body>
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <div class="page-body-wrapper">
            <div class="page-body">
                <!-- Main Content Start -->
                <?= $this->renderSection('main-content') ?>
                <!-- Main Content End -->
            </div>
        </div>
    </div>

</body>

</html>