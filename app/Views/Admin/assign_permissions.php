<?= $this->extend('layout/main_layout') ?>

<?= $this->section('main-content') ?>

<?php
function createOption($data)
{
    $option = '';
    if (isset($data)) {
        foreach ($data as $cdata) {
            $option .= '<option value="' . $cdata['id'] . '">' . $cdata['name'] . '</option>';
        }
    }
    return $option;
}

$rolesOption = createOption($roles);

$grouped_permissions = array();
$permissionsHtml = '';

foreach ($permissions as $permission) {
    $group_name = $permission['permission_group_name'];
    if (!isset($grouped_permissions[$group_name])) {
        $grouped_permissions[$group_name] = array();
    }
    $grouped_permissions[$group_name][] = $permission;
}

foreach ($grouped_permissions as $group_name => $permissions) {
    $permissionsHtml .= '<h6>' . $group_name . '</h6>';

    foreach ($permissions as $permission) {
        $permissionsHtml .= '<div class="form-check form-check-inline checkbox checkbox-dark mb-0">';//id="check_permission_'.$permission['id'].'"  name="permission[]" value="'.$permission['id'].'"
        $permissionsHtml .= '<input type="checkbox" class="form-check-input checkbox_animated" value="' . $permission['id'] . '" name="permission_id[]" id="check_permission_' . $permission['id'] . '">';
        $permissionsHtml .= '<label class="form-check-label" for="check_permission_' . $permission['id'] . '">' . $permission['description'] . '</label>';
        $permissionsHtml .= '</div>';
    }
    $permissionsHtml .= '<div class="separate"> </div>';
}
?>
<div class="container-fluid">
    <div class="row widget-grid">
        <div class="col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-sm-12">
                    <form class="theme-form" id="permissionAssignForm" method="post">
                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                <div class="form-group role-selecter">
                                    <label class="form-label" for="role">Select Role</label>
                                    <select class="form-select" id="role" name="role">
                                        <option>Select Role</option>
                                        <?= $rolesOption ?>
                                    </select>
                                </div>
                                <?= $permissionsHtml ?>
                                <button class="btn btn-primary" type="submit" id="btnPermissionAssign">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/custom/js/assign_permission.js?dt=<?= time(); ?>"></script>

<?= $this->endSection() ?>