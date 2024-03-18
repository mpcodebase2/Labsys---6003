<?= $this->extend('layout/main_layout') ?>

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
</style>
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
<?php
function createOptionSimple($ary){
    $option = '';
    if(isset($ary)){
        foreach ($ary as $data){
            $option.='<option value="'.$data['id'].'">'.ucfirst($data['name']).'</option>';
        }
    }
    return $option;
}
function createSimple($ary){
    $option = '';
    if(isset($ary)){
        foreach ($ary as $data){
            $option.='<option value="'.$data.'">'.ucfirst($data).'</option>';
        }
    }
    return $option;
}

$patientOptions = createOptionSimple($patients);
$testOptions = createOptionSimple($tests);
$doctorOptions = createOptionSimple($doctors);
$statusOptions = createSimple($status);
?>
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
    <div class="modal fade bd-example-modal-xl" id="createAppointmentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="appointment_action_heading">Add Appointment</h4>
                </div>
                <div class="modal-body dark-modal">
                    <form class="theme-form" id="createAppointmentsForm" method="post">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <div class="row">
                            <div class="form-group col-12 col-lg-12 mb-3">
                                <label class="col-form-label pt-0" for="patient_id">Patient<i>*</i></label>
                                <select class="form-select" id="patient_id" name="patient_id">
                                    <option value="">Select</option>
                                    <?php echo $patientOptions;?>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label pt-0" for="test_id">Test<i>*</i></label>
                                <select class="form-select" id="test_id" name="test_id">
                                    <option value="">Select</option>
                                    <?php echo $testOptions;?>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label pt-0" for="doctor_id">Assign a Doctor<i>*</i></label>
                                <select class="form-select" id="doctor_id" name="doctor_id">
                                    <option value="">Select</option>
                                    <?php echo $doctorOptions;?>
                                </select>
                            </div>
                            <div class="form-group field-radio-group  col-12 col-lg-4 mb-3">
                                <label for="appointment_date" class="col-form-label pt-0">Appointment Date<i>*</i></label>
                                <input type="date" class="form-control" name="appointment_date" id="appointment_date" placeholder="Enter Appointment Date">
                            </div>
                            <div class="form-group field-radio-group  col-12 col-lg-4 mb-3">
                                <label for="expected_date" class="col-form-label pt-0">Expected Date<i>*</i></label>
                                <input type="date" class="form-control" name="expected_date" id="expected_date" placeholder="Enter Expected Date">
                            </div>
                            <div class="form-group field-radio-group  col-12 col-lg-4 mb-3">
                                <label for="expected_date" class="col-form-label pt-0">Note</label>
                                <textarea class="form-text" id="note" name="note"></textarea>
                            </div>
                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label" for="amount">Amount<i>*</i></label>
                                <input type="number" class="form-control" min="0.00" max="99999999999999.00" step="0.01" name="amount" id="amount" placeholder="0.00" autocomplete="fnn"/>
                            </div>
                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label" for="paid">Paid<i>*</i></label>
                                <input type="number" class="form-control" min="0.00" max="99999999999999.00" step="0.01" name="paid" id="paid" placeholder="0.00" autocomplete="fnn"/>
                            </div>
                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label" for="due">Due<i>*</i></label>
                                <input type="number" class="form-control" min="0.00" max="99999999999999.00" step="0.01" name="due" id="due" placeholder="0.00" autocomplete="fnn"/>
                            </div>
                            <div class="form-group field-radio-group  col-12 col-lg-4 mb-3">
                                <label for="paid_via" class="radio-group-label">Paid via</label>
                                <select class="form-select" id="paid_via" name="paid_via">
                                    <option value="">Select</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Online">Online</option>
                                    <option value="Card">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-secondary" id="btnCreateCancel">Cancel</button>
                            <button class="btn btn-primary" id="btnAddAppointment" type="submit">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="updateAppointmentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="up_appointment_action_heading">Update Appointment</h4>
                </div>
                <div class="modal-body dark-modal">
                    <form class="theme-form" id="updateAppointmentForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <div class="row">
                            <div class="form-group col-12 col-lg-12 mb-3">
                                <label class="col-form-label pt-0" for="patient_id">Patient<i>*</i></label>
                                <select class="form-select" id="up_patient_id" name="patient_id">
                                    <option value="">Select</option>
                                    <?php echo $patientOptions;?>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label pt-0" for="test_id">Test<i>*</i></label>
                                <select class="form-select" id="up_test_id" name="test_id">
                                    <option value="">Select</option>
                                    <?php echo $testOptions;?>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label pt-0" for="doctor_id">Assign a Doctor<i>*</i></label>
                                <select class="form-select" id="up_doctor_id" name="doctor_id">
                                    <option value="">Select</option>
                                    <?php echo $doctorOptions;?>
                                </select>
                            </div>
                            <div class="form-group field-radio-group  col-12 col-lg-4 mb-3">
                                <label for="appointment_date" class="col-form-label pt-0">Appointment Date<i>*</i></label>
                                <input type="date" class="form-control" name="appointment_date" id="up_appointment_date" placeholder="Enter Appointment Date">
                            </div>
                            <div class="form-group field-radio-group  col-12 col-lg-4 mb-3">
                                <label for="expected_date" class="col-form-label pt-0">Expected Date<i>*</i></label>
                                <input type="date" class="form-control" name="expected_date" id="up_expected_date" placeholder="Enter Expected Date">
                            </div>
                            <div class="form-group field-radio-group  col-12 col-lg-4 mb-3">
                                <label for="expected_date" class="col-form-label pt-0">Note</label>
                                <textarea class="form-text" id="up_note" name="note"></textarea>
                            </div>
                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label" for="amount">Amount<i>*</i></label>
                                <input type="number" class="form-control" min="0.00" max="99999999999999.00" step="0.01" name="amount" id="up_amount" placeholder="0.00" autocomplete="fnn"/>
                            </div>
                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label" for="paid">Paid<i>*</i></label>
                                <input type="number" class="form-control" min="0.00" max="99999999999999.00" step="0.01" name="paid" id="up_paid" placeholder="0.00" autocomplete="fnn"/>
                            </div>
                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label" for="due">Due<i>*</i></label>
                                <input type="number" class="form-control" min="0.00" max="99999999999999.00" step="0.01" name="due" id="up_due" placeholder="0.00" autocomplete="fnn"/>
                            </div>
                            <div class="form-group field-radio-group  col-12 col-lg-4 mb-3">
                                <label for="paid_via" class="radio-group-label">Paid via</label>
                                <select class="form-select" id="up_paid_via" name="paid_via">
                                    <option value="">Select</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Online">Online</option>
                                    <option value="Card">Other</option>
                                </select>
                            </div>

                            <div class="form-group col-12 col-lg-4 mb-3">
                                <label class="col-form-label pt-0" for="up_status">Status<i>*</i></label>
                                <select class="form-select" id="up_status" name="status">
                                    <option value="">Select</option>
                                    <?php echo $statusOptions;?>
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <label class="col-form-label" for="test_result_media">Test Results Files</label>
                                <div class="row" id="test_result_media"></div>
                            </div>
                            <div class="form-group col-12 col-lg-12 mb-3">
                                <label class="col-form-label" for="media_files">Media Files.(Max: 5 Files | 5MB)</label>
                                <input class="form-control" type="file" id="up_media_files" name="media_files[]" multiple accept="image/*, .pdf" data-bs-original-title="" title="">
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-secondary" id="btnUpdateCancel">Cancel</button>
                            <button class="btn btn-primary" id="btnUpdateAppointment" type="submit">Update</button>
                        </div>
                        <input type="hidden" name="appointment_id" id="appointment_id">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete Appointment</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this appointment?</p>
                </div>
                <input type="hidden" name="delete_appointment_id" id="delete_appointment_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="confirmDeleteCancelBtn" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Delete</button>
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
                    url :baseUrl+"admin/api/appointment/all", // json datasource
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
    <script src="<?=base_url()?>assets/custom/js/appointments.js?dt=<?=time();?>"></script>
<?= $this->endSection() ?>