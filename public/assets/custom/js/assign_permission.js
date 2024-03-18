function showLoading() {
    const spinner = document.createElement('div');
    spinner.className = 'spinner';

    const loader = document.createElement('div');
    loader.className = 'loader';

    spinner.appendChild(loader);

    document.body.appendChild(spinner);
}

// Function to hide loading spinner
function hideLoading() {
    const spinner = document.querySelector('.spinner');
    document.body.removeChild(spinner);
}

function clearCheckboxes() {
    let checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = false;
    }
}

$('#role').on('change', function() {
    fetchRolePermissionData();
});

function fetchRolePermissionData(){
    let role_id = $('#role').val();
    if (role_id !== '') {
        // Send an AJAX request to get the role permissions
        $.ajax({
            url: baseUrl+'admin/api/get/permissions/by_role',
            data: {role_id: role_id},
            dataType: 'json',
            beforeSend: function() { showLoading() },
            success: function(permissions) {
                console.log(permissions)
                // Update the checkboxes with the role permissions
                updateCheckboxes(permissions);
                hideLoading()
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR)
                hideLoading()
                let errorMessage = '';
                if (jqXHR.status === 403) {
                    errorMessage = 'The requested rejected. Please refresh and Try again';
                } else if (jqXHR.status === 500) {
                    errorMessage = 'Internal Server Error. Please try again later.';
                } else {
                    errorMessage = 'An error occurred. Please try again later.';
                }
                showError('Error!', errorMessage);
            }
        });
    } else {
        // Clear the checkboxes if no role is selected
        clearCheckboxes();
        hideLoading()
    }
}

// Function to update the checkboxes with the role permissions
function updateCheckboxes(permissions) {
    let checkboxes = $('input[type="checkbox"]');
    checkboxes.prop('checked', false);
    checkboxes.each(function() {
        for (let j = 0; j < permissions.length; j++) {
            if (this.value === permissions[j].permission_id) {
                $(this).prop('checked', true);
                break;
            }else{
                console.log('not found')
            }
        }
    });
}

$('#permissionAssignForm').submit(function(event) {
    event.preventDefault();
    let form_data = $(this).serialize();
    let json_string = JSON.stringify($(this).serializeArray());
    console.log(json_string);

    $.ajax({
        url: baseUrl + 'admin/api/role-permissions/assign',
        type: 'POST',
        data: form_data,
        dataType: 'json',
        beforeSend: function () {
            showLoading()
        },
        success: function (response) {
            hideLoading()
            if (response.status) {
                fetchRolePermissionData()
                showSuccess('Success', 'Successfully updated.');
            }else{
                showError('Error!', response.message);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR)
            hideLoading()
            let errorMessage = '';
            if (jqXHR.status === 403) {
                errorMessage = 'The requested rejected. Please refresh and Try again';
            } else if (jqXHR.status === 500) {
                errorMessage = 'Internal Server Error. Please try again later.';
            } else {
                errorMessage = 'An error occurred. Please try again later.';
            }
            showError('Error!', errorMessage);
        }
    });
});

function toUpperCaseFirstLetter(str){
    str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
    return str;
}

function showSuccess(_title, _subject){
    console.log(_title, _subject)
    _subject = toUpperCaseFirstLetter(_subject)
    _title = toUpperCaseFirstLetter(_title)
    toastr.success(_subject,_title, {
        progressBar: true
    });
}

function showError(_title, _subject){
    console.log(_title, _subject)
    _subject = toUpperCaseFirstLetter(_subject)
    _title = toUpperCaseFirstLetter(_title)
    toastr.error(_subject,_title, {
        progressBar: true
    });
}

function dateTimeToDate(dateTime){
    let date = new Date(dateTime.replace(/-/g, "/"));
    let year = date.getFullYear();
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let day = String(date.getDate()).padStart(2, '0');
    return year + "-" + month + "-" + day;
}