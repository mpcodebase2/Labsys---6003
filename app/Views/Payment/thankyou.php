<?= $this->extend('layout/landing_layout') ?>

<?= $this->section('main-content') ?>


<div class="container-fluid">
    <div class="row widget-grid">
        <div class="col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <br class="card-header">
                            <h2 style="margin-top: 25%;">Thank you. </br>You Appointment create successfully.</h2>
                            </br><i>You will be redirect after 5 Seconds</i>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        // Redirect after 5 seconds
        setTimeout(function() {
            window.location.href = baseUrl; // Replace "http://example.com" with the URL you want to redirect to
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>


<?= $this->endSection() ?>