<?= $this->extend('layout/main_layout') ?>

<?= $this->section('main-content') ?>

<?php
function createOption($data, $selected){
    $option = '';
    if(isset($data)){
        foreach ($data as $cdata){
            $_isSelected = (isset($selected) && $cdata->id == $selected)?'selected':'';
            $option.='<option value="'.$cdata->id.'" '.$_isSelected.'>'.$cdata->name.'</option>';
        }
    }
    return $option;
}
function createOptionSimple($ary){
    $option = '';
    if(isset($ary)){
        foreach ($ary as $data){
            $option.='<option value="'.$data['id'].'">'.$data['name'].'</option>';
        }
    }
    return $option;
}
$cityOptions = createOption($city, '');
$districtOptions = createOption($district, '');
$provinceOptions = createOption($province, '');
$countryOptions = createOption($country, '');


?>

<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/vendors/datatables.css">
<div class="container-fluid">
    <div class="row widget-grid">
        <div class="col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="align-right"> <button class="btn btn-primary mb-3" id="btnCreatePatient">Create Patient</button></div>
                            <div class="table-responsive">
                                <table class="display datatables" id="patient-data-table">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Registered Date</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>NIC</th>
                                        <th>Gender</th>
                                        <th>Date of Birth</th>
                                        <th>Is Active</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Registered Date</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>NIC</th>
                                        <th>Gender</th>
                                        <th>Date of Birth</th>
                                        <th>Is Active</th>
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

<div class="modal fade bd-example-modal-xl" id="createPatientModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="user_action_heading">Create Patient</h4>
            </div>
            <div class="modal-body dark-modal">
                <form class="theme-form" id="createPatientsForm" method="post">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="row">
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="password">Password<i>*</i></label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="nic">NIC</label>
                            <input type="text" class="form-control" name="nic" id="nic" placeholder="Enter NIC" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter First Name" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="last_name">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="gender">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="dob">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" id="dob" placeholder="Enter Date of Birth">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="address_ln1">Address Line 1</label>
                            <input type="text" class="form-control" name="address_ln1" id="address_ln1" placeholder="Enter Address Line 1" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="address_ln2">Address Line 2</label>
                            <input type="text" class="form-control" name="address_ln2" id="address_ln2" placeholder="Enter Address Line 2" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="city">City</label>
                            <input class="form-control" id="city" data-autofill-values="colombo|jaffna" name="city" type="text"  placeholder="Wellawatte" maxlength="100" size="50" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="district">District</label>
                            <select class="form-select" id="district" name="district">
                                <option value="">Select</option>
                                <?php echo $districtOptions;?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="province">Province</label>
                            <select class="form-select" id="province" name="province">
                                <option value="">Select</option>
                                <?php echo $provinceOptions;?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="country">Country</label>
                            <select class="form-select" id="country" name="country">
                                <option value="">Select</option>
                                <?php echo $countryOptions;?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="telephone">Telephone</label>
                            <input type="text" class="form-control" name="telephone" id="telephone" placeholder="Enter Telephone" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="mobile">Mobile</label>
                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="occupation">Occupation</label>
                            <input type="text" class="form-control" name="occupation" id="occupation" placeholder="Enter Occupation" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="is_active">Is Active</label>
                            <select class="form-select" id="is_active" name="is_active">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="is_staff">Is Staff</label>
                            <select class="form-select" id="is_staff" name="is_staff">
                                <option value="">Select</option>
                                <option value="1">Staff</option>
                                <option value="0">Not Staff</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-secondary" id="btnCreateCancel">Cancel</button>
                        <button class="btn btn-primary" id="btnCreate" type="submit">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="updatePatientModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="up_user_action_heading">Update Patient</h4>
            </div>
            <div class="modal-body dark-modal">
                <form class="theme-form" id="updatePatientForm" method="post">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="row">
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="up_email" placeholder="Enter Email" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="password">Password<i> (Leave empty if same)</i></label>
                            <input type="password" class="form-control" name="password" id="up_password" placeholder="Enter Password" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="nic">NIC</label>
                            <input type="text" class="form-control" name="nic" id="up_nic" placeholder="Enter NIC" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="up_first_name" placeholder="Enter First Name" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="last_name">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="up_last_name" placeholder="Enter Last Name" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="gender">Gender</label>
                            <select class="form-select" id="up_gender" name="gender">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="dob">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" id="up_dob" placeholder="Enter Date of Birth">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="address_ln1">Address Line 1</label>
                            <input type="text" class="form-control" name="address_ln1" id="up_address_ln1" placeholder="Enter Address Line 1" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="address_ln2">Address Line 2</label>
                            <input type="text" class="form-control" name="address_ln2" id="up_address_ln2" placeholder="Enter Address Line 2" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="city">City</label>
                            <input class="form-control" id="up_city" data-autofill-values="colombo|jaffna" name="city" type="text"  placeholder="Wellawatte" maxlength="100" size="50" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="district">District</label>
                            <select class="form-select" id="up_district" name="district">
                                <option value="">Select</option>
                                <?php echo $districtOptions;?>
                            </select>                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="province">Province</label>
                            <select class="form-select" id="up_province" name="province">
                                <option value="">Select</option>
                                <?php echo $provinceOptions;?>
                            </select>                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="country">Country</label>
                            <select class="form-select" id="up_country" name="country">
                                <option value="">Select</option>
                                <?php echo $countryOptions;?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="telephone">Telephone</label>
                            <input type="text" class="form-control" name="telephone" id="up_telephone" placeholder="Enter Telephone" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="mobile">Mobile</label>
                            <input type="text" class="form-control" name="mobile" id="up_mobile" placeholder="Enter Mobile" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="occupation">Occupation</label>
                            <input type="text" class="form-control" name="occupation" id="up_occupation" placeholder="Enter Occupation" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="is_active">Is Active</label>
                            <select class="form-select" id="up_is_active" name="is_active">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="is_staff">Is Staff</label>
                            <select class="form-select" id="up_is_staff" name="is_staff">
                                <option value="">Select</option>
                                <option value="1">Staff</option>
                                <option value="0">Not Staff</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-secondary" id="btnUpdateCancel">Cancel</button>
                        <button class="btn btn-primary" id="btnUpdate" type="submit">Update</button>
                    </div>
                    <input type="hidden" name="id" id="patient_id">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" patient="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" patient="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete Patient</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Patient?</p>
            </div>
            <input type="hidden" name="delete_patient_id" id="delete_patient_id">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="confirmDeleteCancelBtn" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
<script>

    <?php
    $cities = array();
    foreach ($city as $c){
        $cities[] = $c->name;
    }
    $nationality = array('Sri Lankan', 'Indian', 'Other');
    ?>

    let city_list = <?php echo json_encode($cities);?>;
    let nationality = <?php echo json_encode($nationality);?>;

    $('#city').autocomplete_init(city_list);
    $('#up_city').autocomplete_init(city_list);

    $('#nationality').autocomplete_init(nationality);
    $('#up_nationality').autocomplete_init(nationality);

    let patientTable;
    $(function() {

        patientTable = $('#patient-data-table').DataTable( {
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[ 0, "DESC" ]],
            "lengthMenu": [ [30, 60, 200, 400, -1], [30, 60, 200, 400, "All"] ],
            "pageLength": 30,
            "fixedColumns": true,
            "ajax":{
                url :baseUrl+"admin/api/patient/all", // json datasource
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
                    console.log( 'Error: patient-data-table DataTables: ' + textStatus );
                    console.log( jqXHR );
                    console.log( errorThrown );
                    $(".patient-data-table-error").html("");
                    $("#patient-data-table").append('<tbody class="patient-data-table-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#patient-data-table_processing").css("display","none");

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
        $('#patient-data-table-select-all').on('click', function(){
            // Check/uncheck all checkboxes in the table
            var rows = patientTable.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

    });
</script>
<script src="<?=base_url()?>assets/js/just-validate-plugin-date/dist/just-validate-plugin-date.production.min.js"></script>
<script src="<?=base_url()?>assets/js/just-validate/dist/just-validate.production.min.js"></script>
<script src="<?=base_url()?>assets/custom/js/patients.js?dt=<?=time();?>"></script>
<?= $this->endSection() ?>
