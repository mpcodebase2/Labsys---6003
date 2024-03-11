
<?= $this->section('main-content') ?>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/vendors/datatables.css">
    <div class="container-fluid">
        <div class="row widget-grid">
            <div class="col-sm-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="align-right"> <button class="btn btn-primary mb-3" id="btnCreateAppointment">Add Appointment</button></div>
                                <div class="table-responsive">
                                    <table class="display datatables" id="appointments-data-table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Test</th>
                                            <th>Patient</th>
                                            <th>Doctor</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Paid</th>
                                            <th>Due</th>
                                            <th>Status</th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Test</th>
                                            <th>Patient</th>
                                            <th>Doctor</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Paid</th>
                                            <th>Due</th>
                                            <th>Status</th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>