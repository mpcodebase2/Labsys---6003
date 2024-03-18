<?= $this->extend('layout/public_dashboard_layout') ?>

<?= $this->section('main-content') ?>
    <style>
        .media-preview {
            width: 150px;
            margin: 5px 10px;
            border: 5px solid #cccc;
            border-radius: 15px;
        }
        img.media-preview-icon {
            width: 100%;
            height: 75%;
            margin: 10px 0;
        }
        a.download-media-btn {
            display: inline-block;
            width: 40px;
            height: 40px;
            position: absolute;
            left: 10%;
            bottom: 1%;
            border: 4px solid #ccc;
            border-radius: 10px;
            background-color: #ffff;
        }
        img.donwload-icon {
            width: 95%;
            margin: auto;
        }
        div#previewModal {
            z-index: 99999;
            background-color: #00000042;
        }
        div#preview-link-frame {
            height: 80vh;
        }
        .appoint-response {
            width: 70%;
            margin: auto;
        }
        table.appoint-response td {
            padding: 10px;
            border: 1px solid #ccc;
            font-size: 15px;
        }
        button#btnUpdateCancel {
            margin: 34px auto;
            width: 50%;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/vendors/datatables.css">
    <div class="container-fluid">
        <div class="row widget-grid">
            <div class="col-sm-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
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

    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="previewModal" id="previewModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="previewModal">Preview</h4>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body dark-modal" id="preview-link-frame">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="updateAppointmentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="up_appointment_action_heading">Appointment Number- <span id="appointment_id"></span></h4>
                </div>
                <div class="modal-body dark-modal">

                    <div class="row">
                        <table class="appoint-response">
                            <tr>
                                <td class="left-td">Test:</td>
                                <td class="left-td"><span id="test"></span></td>
                            </tr>
                            <tr>
                                <td class="left-td">Doctor:</td>
                                <td class="left-td"><span id="doctor"></span></td>
                            </tr>
                            <tr>
                                <td class="left-td">Appointment Date:</td>
                                <td class="left-td"><span id="appointment_date"></span></td>
                            </tr>
                            <tr>
                                <td class="left-td">Note:</td>
                                <td class="left-td"><span id="note"></span></td>
                            </tr>
                            <tr>
                                <td class="left-td">Amount:</td>
                                <td class="left-td"><span id="amount"></span></td>
                            </tr>
                            <tr>
                                <td class="left-td">Paid:</td>
                                <td class="left-td"><span id="paid"></span></td>
                            </tr>
                            <tr>
                                <td class="left-td">Due:</td>
                                <td class="left-td"><span id="due"></span></td>
                            </tr>
                            <tr>
                                <td class="left-td">Test Results Files:</td>
                                <td class="left-td"><div class="row" id="test_result_media"></div></td>
                            </tr>
                        </table>
                        <button class="btn btn-secondary" id="btnUpdateCancel">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="<?=base_url()?>assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script>
        let appointmentTable;
        $(function() {

            appointmentTable = $('#appointments-data-table').DataTable( {
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "order": [[ 0, "asc" ]],
                "lengthMenu": [ [30, 60, 200, 400, -1], [30, 60, 200, 400, "All"] ],
                "pageLength": 30,
                "fixedColumns": true,
                "ajax":{
                    url :baseUrl+"api/appointment/all", // json datasource
                    type: "post",
                    data: {
                        csrf_test_name: tk,
                    },
                    beforeSend: function() {

                    },

                    // method  , by default get
                    // success: function (data){
                    //     console.log(data)
                    // },
                    error: function(jqXHR, textStatus, errorThrown){  // error handling
                        console.log( 'Error: appointments-data-table DataTables: ' + textStatus );
                        console.log( jqXHR );
                        console.log( errorThrown );
                        $(".appointments-data-table-error").html("");
                        $("#appointments-data-table").append('<tbody class="appointments-data-table-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#appointments-data-table_processing").css("display","none");

                    }
                }

            });
            setInterval(function(){
                //newNotification();
                //dataTable.ajax.reload();
                //newNotification();
                //alert('Number of row entries: '+dataTable.column( 0 ).data().length);
            }, 100000);
            // Handle click on "Select all" control
            $('#appointments-data-table-select-all').on('click', function(){
                // Check/uncheck all checkboxes in the table
                var rows = appointmentTable.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

        });
    </script>
    <script src="<?=base_url()?>assets/js/just-validate-plugin-date/dist/just-validate-plugin-date.production.min.js"></script>
    <script src="<?=base_url()?>assets/js/just-validate/dist/just-validate.production.min.js"></script>
    <script src="<?=base_url()?>assets/custom/js/appointments-p.js?dt=<?=time();?>"></script>
<?= $this->endSection() ?>