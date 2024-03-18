<?= $this->extend('layout/public_dashboard_layout') ?>

<?= $this->section('main-content') ?>


<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h4>Dashboard </h4>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                            <svg class="stroke-icon">
                                <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                            </svg></a></li>
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Default      </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid">
    <div class="row widget-grid">
        <div class="col-xxl-4 col-sm-6 box-col-6">
            <div class="card profile-box">
                <div class="card-body">
                    <div class="media media-wrapper justify-content-between">
                        <div class="media-body">
                            <div class="greeting-user">
                                <h4 class="f-w-600">Hello <?= session('firstName'); ?> <?= session('lastName'); ?>!</h4>
                                <h5 class="f-w-600">Welcome to ABC Labs</h5>
                                <p>Precision in Diagnosis, Excellence in Care.</p>
                                <div class="whatsnew-btn"><a href="<?=base_url()?>create-appointment" class="btn btn-outline-white">Make an Appointment!</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=base_url()?>assets/js/chart/apex-chart/apex-chart.js"></script>

<script>
    $(document).ready(function(){
        // Fetch chart data from CodeIgniter backend
        fetch(baseUrl+'appointments/get-chart-cata')
            .then(response => response.json())
            .then(chartData => {
                // Constructing series data
                var seriesData = [];
                var categories = chartData.dates;

                // Loop through each status and its data
                for (var i = 0; i < chartData.statuses.length; i++) {
                    seriesData.push({
                        name: chartData.statuses[i].name,
                        data: chartData.statuses[i].data
                    });
                }

                // Constructing ApexCharts options
                var options1 = {
                    chart: {
                        height: 250,
                        type: "area",
                        toolbar: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: "smooth",
                    },
                    series: seriesData,
                    xaxis: {
                        type: "datetime",
                        categories: categories,
                    },
                    tooltip: {
                        x: {
                            format: "dd/MM/yy HH:mm",
                        },
                    },
                    colors: ['#f1c40f', '#e67e22', '#27ae60', '#8e44ad', '#34495e', '#f39c12','#c0392b' ],
                };
                $statuses = ['Pending', 'In progress', 'Completed', 'Follow-up required', 'On hold', 'Rescheduled', 'Cancelled'];
                // Render the chart
                var chart1 = new ApexCharts(document.querySelector("#area-spaline"), options1);
                chart1.render();
            })
            .catch(error => console.error('Error fetching data:', error));

    })
</script>

<?= $this->endSection() ?>
