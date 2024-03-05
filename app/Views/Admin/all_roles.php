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
                            <div class="align-right"> <button class="btn btn-primary mb-3" id="btnCreateRole">Create Role</button></div>
                            <div class="table-responsive">
                                <table class="display datatables" id="role-data-table">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Role Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Role Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
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

<div class="modal fade bd-example-modal-xl" id="createRoleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="user_action_heading">Create Role</h4>
            </div>
            <div class="modal-body dark-modal">
                <form class="theme-form" id="createRolesForm" method="post">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="row">
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="first_name">Role Name<i>*</i></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Role Name" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="description">Description</label>
                            <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="active">Active Status<i>*</i></label>
                            <select class="form-select" id="active" name="active">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </div>
                    </div>
                    <div id="recaptcha-container"></div>
                    <div class="card-footer text-end">
                        <button class="btn btn-secondary" id="btnCreateCancel">Cancel</button>
                        <button class="btn btn-primary" id="btnCreate" type="submit">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="updateRoleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="up_user_action_heading">Update Role</h4>
            </div>
            <div class="modal-body dark-modal">
                <form class="theme-form" id="updateRoleForm" method="post">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="row">
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="first_name">Role Name<i> (Can't update!)</i></label>
                            <input type="text" class="form-control" id="up_name" placeholder="Enter Role Name" autocomplete="fnn" readonly>
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="description">Description</label>
                            <input type="text" class="form-control" name="description" id="up_description" placeholder="Enter Description" autocomplete="fnn">
                        </div>
                        <div class="form-group col-12 col-lg-4 mb-3">
                            <label class="col-form-label" for="active">Active Status<i>*</i></label>
                            <select class="form-select" id="up_active" name="active">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </div>
                    </div>
                    <div id="recaptcha-container"></div>
                    <div class="card-footer text-end">
                        <button class="btn btn-secondary" id="btnUpdateCancel">Cancel</button>
                        <button class="btn btn-primary" id="btnUpdate" type="submit">Update</button>
                    </div>
                    <input type="hidden" name="id" id="role_id">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete Role</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Role?</p>
            </div>
            <input type="hidden" name="delete_role_id" id="delete_role_id">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="confirmDeleteCancelBtn" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
<script>
    let roleTable;
    $(function() {

        roleTable = $('#role-data-table').DataTable( {
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[ 0, "DESC" ]],
            "lengthMenu": [ [30, 60, 200, 400, -1], [30, 60, 200, 400, "All"] ],
            "pageLength": 30,
            "fixedColumns": true,
            "ajax":{
                url :baseUrl+"admin/api/roles/all", // json datasource
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
                    console.log( 'Error: role-data-table DataTables: ' + textStatus );
                    console.log( jqXHR );
                    console.log( errorThrown );
                    $(".role-data-table-error").html("");
                    $("#role-data-table").append('<tbody class="role-data-table-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#role-data-table_processing").css("display","none");

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
        $('#role-data-table-select-all').on('click', function(){
            // Check/uncheck all checkboxes in the table
            var rows = roleTable.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

    });
</script>
<script src="<?=base_url()?>assets/js/just-validate-plugin-date/dist/just-validate-plugin-date.production.min.js"></script>
<script src="<?=base_url()?>assets/js/just-validate/dist/just-validate.production.min.js"></script>
<script src="<?=base_url()?>assets/custom/js/roles.js?dt=<?=time();?>"></script>
<?= $this->endSection() ?>