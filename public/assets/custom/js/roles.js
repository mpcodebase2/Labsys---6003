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
function clearForm(){
    $('form :input:not(:hidden)').val('');
    $('form select').val('');
}

function submitCreateForm() {
    $.ajax({
        type: "POST",
        url: baseUrl + "admin/api/roles/create",
        data: $('#createRolesForm').serialize(),
        dataType: 'json',
        beforeSend: function() { showLoading() },
        success: function (response) {
            console.log(response);
            hideLoading()
            console.log(response.data.action);
            if (response.status) {
                if (response.data.action === 'redirect') {
                    window.location.href = baseUrl + response.data.url;
                }
                showSuccess('Success', 'Role created successfully!')
                roleTable.ajax.reload();
                clearForm();
                $("#createRoleModal").modal('hide');
            } else {
                if (response.data.action === 'redirect') {
                    window.location.href = baseUrl + response.data.url;
                } else if (response.data.action === 'error') {
                    showCreateAjaxError(response.data.error_data)
                } else if (response.data.action === 'recaptcha') {
                    submitCreateForm()
                } else {
                    let errorMessage = 'An error occurred. Please try again later/Contact us!';
                    showError('Error!', errorMessage);
                }
            }
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
}

let createFormValidate;
if ($('#createRolesForm').length) {
    createFormValidate = new JustValidate('#createRolesForm', {
        validateBeforeSubmitting: true,
        errorFieldCssClass: ['invalid-field'],
        successFieldCssClass: ['valid-field'],
        errorLabelCssClass: ['invalid-label'],
        successLabelCssClass: ['valid-label']
    });

    createFormValidate
        .addField('#name', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#description', [{rule: 'maxLength', value: 250}])
        .addField('#active', [{rule: 'required'}])
        .onSuccess((e) => {
            e.preventDefault();
            submitCreateForm();
        });
}
function showCreateAjaxError(data){
    try {
        if (Object.keys(data).length > 0) {
            $.each(data, function (index, value) {
                let elementId = `#${index}`;
                createFormValidate.showErrors({[elementId]: value})
            });
        }
    }catch (e) {
        errorMessage = 'An error occurred. Please try again later.';
        showError('Error!', errorMessage);
    }
}


//Update
function updateForm(){
    let form = document.querySelector('#updateRoleForm');
    let formData = $(form).serializeArray();
    console.log(formData);
    $.ajax({
        type: "POST",
        url: baseUrl + "admin/api/roles/update",
        data: $('#updateRoleForm').serialize(),
        dataType: 'json',
        beforeSend: function() { showLoading() },
        success: function (response) {
            console.log(response);
            hideLoading()
            if(response.status) {
                if (response.data.action === 'redirect') {
                    window.location.href = baseUrl + response.data.url;
                }
                showSuccess('Success', 'Role updated successfully!')
                roleTable.ajax.reload();
                $("#updateRoleModal").modal('hide');
                clearForm();
            } else {
                if (response.data.action === 'redirect') {
                    window.location.href = baseUrl + response.data.url;
                } else if (response.data.action === 'error') {
                    //Showing Errors
                    showUpdateAjaxError(response.data.error_data);
                } else {
                    errorMessage = 'An error occurred. Please try again later/Contact us!';
                    showError('Error!', errorMessage);
                }
            }
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
}

let updateFormValidate;
if ($('#updateRoleForm').length) {
    updateFormValidate = new JustValidate('#updateRoleForm', {
        validateBeforeSubmitting: true,
        errorFieldCssClass: ['invalid-field'],
        successFieldCssClass: ['valid-field'],
        errorLabelCssClass: ['invalid-label'],
        successLabelCssClass: ['valid-label']
    });

    updateFormValidate
        .addField('#up_description', [{rule: 'maxLength', value: 250}])
        .addField('#up_active', [{rule: 'required'}])
        .onSuccess((e) => {
            if (e && typeof e.preventDefault === 'function') {
                e.preventDefault();
                updateForm();
            }
        });
}
function viewRoles(id){
    updateFormValidate.refresh();
    clearForm();
    $('#user_id').val(id);
    $.ajax({
        type: "POST",
        url: baseUrl + "admin/api/roles/get/data",
        data: {
            csrf_test_name: tk,
            id:id
        },
        dataType: 'json',
        beforeSend: function() { showLoading() },
        success: function (response) {
            hideLoading()
            if (response.status) {
                $('#up_name').val(response.data.name);
                $('#up_description').val(response.data.description);
                $('#up_active').val(response.data.active);
                $('#role_id').val(response.data.id);
                updateFormValidate.revalidate().then(isValid => {})
                $("#updateRoleModal").modal('show');

            } else {
                if (response.data.action === 'redirect') {
                    window.location.href = baseUrl + response.data.url;
                } else {
                    errorMessage = 'An error occurred. Please try again later/Contact us!';
                    showError('Error!', errorMessage);
                }
            }
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

}

function showUpdateAjaxError(data){
    try {
        if (Object.keys(data).length > 0) {
            $.each(data, function (index, value) {
                let elementId = `#up_${index}`;
                console.log(elementId)
                updateFormValidate.showErrors({[elementId]: value})
            });
        }
    }catch (e) {
        console.log(e)
        console.log(e.message)
        errorMessage = 'An error occurred. Please try again later.';
        showError('Error!', errorMessage);
    }
}

function deleteRoles(id){
    $('#confirmDeleteModal').modal('show');
    $('#delete_role_id').val(id);
}
$('#confirmDeleteCancelBtn').click(function (){
    $('#delete_role_id').val('')
    $('#confirmDeleteModal').modal('hide');
});
$('#confirmDeleteBtn').click(function() {
    $('#confirmDeleteModal').modal('hide');
    let id = $('#delete_role_id').val();
    if(id) {
        $.ajax({
            type: "POST",
            url: baseUrl + "admin/api/roles/delete",
            data: {
                csrf_test_name: tk,
                id: id
            },
            dataType: 'json',
            beforeSend: function () {
                showLoading()
            },
            success: function (response) {
                hideLoading()
                if (response.status) {
                    $('#delete_role_id').val('')
                    roleTable.ajax.reload();
                    showSuccess('Success', 'Successfully deleted.')
                } else {
                    if (response.data.action === 'redirect') {
                        window.location.href = baseUrl + response.data.url;
                    } else {
                        errorMessage = 'An error occurred. Please try again later/Contact us!';
                        showError('Error!', errorMessage);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                hideLoading()
                let errorMessage = '';
                if (jqXHR.status === 403) {
                    errorMessage = 'The requested rejected. Please refresh and Try again';
                    location.reload();
                } else if (jqXHR.status === 500) {
                    errorMessage = 'Internal Server Error. Please try again later.';
                } else {
                    errorMessage = 'An error occurred. Please try again later.';
                }
                showError('Error!', errorMessage);
            }
        });
    }else{
        showError('Error!', 'User id empty');
    }
});

$('#btnCreateRole').click(function (){
    clearForm();
    createFormValidate.refresh();
    $("#createRoleModal").modal('show');
})

$('#btnCreateCancel').click(function() {
    clearForm()
    $("#createRoleModal").modal('hide');
});

$('#btnUpdateCancel').click(function() {
    clearForm()
    $("#updateRoleModal").modal('hide');
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