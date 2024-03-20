<?= $this->extend('layout/main_layout') ?>

<?= $this->section('main-content') ?>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/vendors/datatables.css">
<div class="container-fluid">
    <div class="row widget-grid">
        <div class="col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="align-right"> <button class="btn btn-primary mb-3" id="addUser">Add User</button></div>
                            <div class="table-responsive">
                                <table class="display datatables" id="users-data-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>NIC</th>
                                        <th>Username</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>NIC</th>
                                        <th>Username</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Role</th>
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
$rolesOptions = createOptionSimple($userRoles);
?>
<div class="modal fade bd-example-modal-xl" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="user_action_heading">Add User</h4>
            </div>
            <div class="modal-body dark-modal">
                <form class="theme-form" id="addUserForm" method="post">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="row">
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="first_name">First Name<i>*</i></label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter Firstname" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="last_name">Last Name<i>*</i></label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Lastname" autocomplete="lnn">
                        </div>
                        <div class="form-group field-radio-group  col-12 col-lg-4 mb-3">
                            <label for="gender" class="radio-group-label">Gender<i>*</i></label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="username">Username<i>*</i></label>
                            <input class="form-control" id="username" name="username" type="text" placeholder="Enter Username" autocomplete="new-username">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="password">Password<i>*</i></label>
                            <input class="form-control" id="password" name="password" type="password" placeholder="Enter Password" autocomplete="new-password">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="passconf">Confirm Password<i>*</i></label>
                            <input class="form-control" id="passconf" name="passconf" type="password" placeholder="Enter Password" autocomplete="new-password">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="email" placeholder="Enter Email" >
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="specialization">Specialization<i> (If Doctor)</i> </label>
                            <input class="form-control" id="specialization" name="specialization" type="text" placeholder="Enter Specialization" >
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="phone">Phone<i>*</i></label>
                            <input class="form-control" id="phone" name="phone" type="phone" placeholder="Enter Phone">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="user_role">User type<i>*</i></label>
                            <select class="form-select" id="user_role" name="user_role">
                                <option value="">Select</option>
                                <?php echo $rolesOptions;?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="active_status">Active Status<i>*</i></label>
                            <select class="form-select" id="active" name="active">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </div>
                    </div>
                    <div id="recaptcha-container"></div>
                    <div class="card-footer text-end">
                        <button class="btn btn-secondary" id="btnAddUserCancel">Cancel</button>
                        <button class="btn btn-primary" id="btnAddUser" type="submit">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="updateUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="up_user_action_heading">Update User</h4>
            </div>
            <div class="modal-body dark-modal">
                <form class="theme-form" id="updateUserForm" method="post">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="row">
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="first_name">First Name<i>*</i></label>
                            <input type="text" class="form-control" name="first_name" id="up_first_name" placeholder="Enter Firstname" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="last_name">Last Name<i>*</i></label>
                            <input type="text" class="form-control" name="last_name" id="up_last_name" placeholder="Enter Lastname" autocomplete="lnn">
                        </div>
                        <div class="form-group field-radio-group  col-12 col-lg-4 mb-3">
                            <label for="gender" class="radio-group-label">Gender<i>*</i></label>
                            <select class="form-select" id="up_gender" name="gender">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="username">Username<i> (Can't update!)</i></label>
                            <input class="form-control" id="up_username" name="read_only_username" type="text" placeholder="Enter Username" autocomplete="new-username" readonly>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="password">Password<i> (Leave empty if same)</i></label>
                            <input class="form-control" id="up_password" name="password" type="password" placeholder="Enter Password" autocomplete="new-password">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="passconf">Confirm Password<i>(Leave empty if same)</i></label>
                            <input class="form-control" id="up_passconf" name="passconf" type="password" placeholder="Enter Password" autocomplete="new-password">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="email">Email</label>
                            <input class="form-control" id="up_email" name="email" type="email" placeholder="Enter Email" >
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="specialization">Specialization<i> (If Doctor)</i> </label>
                            <input class="form-control" id="up_specialization" name="specialization" type="text" placeholder="Enter Specialization" >
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="phone">Phone<i>*</i></label>
                            <input class="form-control" id="up_phone" name="phone" type="phone" placeholder="Enter Phone">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="user_role">User type<i>*</i></label>
                            <select class="form-select" id="up_user_role" name="user_role">
                                <option value="">Select</option>
                                <?php echo $rolesOptions;?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label pt-0" for="active_status">Active Status<i>*</i></label>
                            <select class="form-select" id="up_active" name="active">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-secondary" id="btnUpdateUserCancel">Cancel</button>
                        <button class="btn btn-primary" id="btnUpdateUser" type="submit">Update</button>
                    </div>
                    <input type="hidden" name="user_id" id="user_id">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete User</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
            </div>
            <input type="hidden" name="delete_user_id" id="delete_user_id">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="confirmDeleteCancelBtn" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
<script src="<?=base_url()?>assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
<script>
    let userTable;
    $(function() {

        userTable = $('#users-data-table').DataTable( {
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [ [30, 60, 200, 400, -1], [30, 60, 200, 400, "All"] ],
            "pageLength": 30,
            "fixedColumns": true,
            "ajax":{
                url :baseUrl+"admin/api/user/all", // json datasource
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
                    console.log( 'Error: users-data-table DataTables: ' + textStatus );
                    console.log( jqXHR );
                    console.log( errorThrown );
                    $(".users-data-table-error").html("");
                    $("#users-data-table").append('<tbody class="users-data-table-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#users-data-table_processing").css("display","none");

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
        $('#users-data-table-select-all').on('click', function(){
            // Check/uncheck all checkboxes in the table
            var rows = userTable.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

    });
</script>
<!--<script src="https://www.google.com/recaptcha/api.js?onload=ReCaptchaCallbackV3&render=--><?php //echo $this->config->item('google_key');?><!--"></script>-->
<script src="<?=base_url()?>assets/js/just-validate-plugin-date/dist/just-validate-plugin-date.production.min.js"></script>
<script src="<?=base_url()?>assets/js/just-validate/dist/just-validate.production.min.js"></script>
<script src="<?=base_url()?>assets/custom/js/users.js?dt=<?=time();?>"></script>
<?= $this->endSection() ?>