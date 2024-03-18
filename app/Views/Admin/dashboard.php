<?= $this->extend('layout/main_layout') ?>

<?= $this->section('main-content') ?>

<?php
function format_number($number)
{
    return number_format($number, 2, '.', ',');
}

?>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           <div class="row">
               <div class="col-sm-6">
                   <div class="card course-box">
                       <div class="card-body">
                           <div class="course-widget">
                               <div class="course-icon">
                                   <svg class="fill-icon">
                                       <use href="../assets/svg/icon-sprite.svg#new-order"></use>
                                   </svg>
                               </div>
                               <div>
                                   <h4 class="mb-0"><?=$totalAppointments?></h4><span class="f-light">Appointment Created</span>
                                   <a class="btn btn-light f-light" href="<?=base_url()?>admin/appointment/all">Create New<span class="ms-2">
                                <svg class="fill-icon f-light">
                                  <use href="../assets/svg/icon-sprite.svg#arrowright"></use>
                                </svg></span></a>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="col-sm-6">
                   <div class="card course-box">
                       <div class="card-body">
                           <div class="course-widget">
                               <div class="course-icon warning">
                                   <svg class="fill-icon">
                                       <use href="../assets/svg/icon-sprite.svg#course-2"></use>
                                   </svg>
                               </div>
                               <div>
                                   <h4 class="mb-0"><?=$totalPatients?></h4><span class="f-light">Patient registerd</span>
                                   <a class="btn btn-light f-light" href="<?=base_url()?>admin/patient/all">Create New<span class="ms-2">
                                <svg class="fill-icon f-light">
                                  <use href="../assets/svg/icon-sprite.svg#customers"></use>
                                </svg></span></a>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
        </div>
        <div class="col-xxl-8 col-sm-6 box-col-6">
            <div class="card">
                <div class="card-header">
                    <h5>Appointment by status</h5>
                </div>
                <div class="card-body">
                    <div id="area-spaline"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card small-widget">
                <div class="card-body primary"> <span class="f-light">Total Appintments</span>
                    <div class="d-flex align-items-end gap-1">
                        <h4><?=$totalAppointments?></h4>
                    </div>
                    <div class="bg-gradient">
                        <svg class="stroke-icon svg-fill">
                            <use href="../assets/svg/icon-sprite.svg#new-order"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card small-widget">
                <div class="card-body warning"><span class="f-light">Total Patients</span>
                    <div class="d-flex align-items-end gap-1">
                        <h4><?=$totalPatients?></h4>
                    </div>
                    <div class="bg-gradient">
                        <svg class="stroke-icon svg-fill">
                            <use href="../assets/svg/icon-sprite.svg#customers"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card small-widget">
                <div class="card-body secondary"><span class="f-light">Total Income</span>
                    <div class="d-flex align-items-end gap-1">
                        <h4><?=format_number($totalIncome)?></h4>
                    </div>
                    <div class="bg-gradient">
                        <svg class="stroke-icon svg-fill">
                            <use href="../assets/svg/icon-sprite.svg#sale"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card small-widget">
                <div class="card-body success"><span class="f-light">Received Income</span>
                    <div class="d-flex align-items-end gap-1">
                        <h4><?=format_number($paidIncome)?></h4>
                    </div>
                    <div class="bg-gradient">
                        <svg class="stroke-icon svg-fill">
                            <use href="../assets/svg/icon-sprite.svg#profit"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card small-widget">
                <div class="card-body success"><span class="f-light">Balance Income</span>
                    <div class="d-flex align-items-end gap-1">
                        <h4><?=format_number($balanceIncome)?></h4>
                    </div>
                    <div class="bg-gradient">
                        <svg class="stroke-icon svg-fill">
                            <use href="../assets/svg/icon-sprite.svg#profit"></use>
                        </svg>
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
