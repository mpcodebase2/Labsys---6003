// const updateUserModal = document.getElementById('updateUserModal');
// let updateModal = new bootstrap.Modal(updateUserModal, {
//     backdrop: 'static',
//     keyboard: false // optional, disables the escape key from closing the modal
// })

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
    //$('form :radio').prop('checked', false);
    $('form select').val('');
}



function submitRegisterForm() {
    $.ajax({
        type: "POST",
        url: baseUrl + "admin/api/user/add",
        data: $('#addUserForm').serialize(),
        dataType: 'json',
        beforeSend: function() { showLoading() },
        success: function (response) {
            console.log(response);
            //ReCaptchaCallbackV3()
            hideLoading()
            console.log(response.data.action);

            if (response.status) {
                if (response.data.action === 'redirect') {
                    window.location.href = baseUrl + response.data.url;
                }
                showSuccess('Success', 'User created successfully!')
                userTable.ajax.reload();
                $("#addUserModal").modal('hide');
            } else {
                //ReCaptchaCallbackV3()
                if (response.data.action === 'redirect') {
                    window.location.href = baseUrl + response.data.url;
                } else if (response.data.action === 'error') {
                    //Showing Errors
                    showRegisterAjaxError(response.data.error_data)
                } else if (response.data.action === 'recaptcha') {
                    submitRegisterForm()
                } else {
                    errorMessage = 'An error occurred. Please try again later/Contact us!';
                    showError('Error!', errorMessage);
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR)
            //ReCaptchaCallbackV3()
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


let registerFormValidate;
if ($('#addUserForm').length) {
    registerFormValidate = new JustValidate('#addUserForm', {
        validateBeforeSubmitting: true,
        errorFieldCssClass: ['invalid-field'],
        successFieldCssClass: ['valid-field'],
        errorLabelCssClass: ['invalid-label'],
        successLabelCssClass: ['valid-label']
    });

    registerFormValidate
        .addField('#first_name', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#last_name', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#gender', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        //.addRequiredGroup(document.querySelector('#radio-group'), 'Please select gender')
        .addField('#username', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#password', [
            {rule: 'required'},
            {rule: 'minLength', value: 8, errorMessage: 'Password Must be at least 8 characters long'},
            {rule: 'maxLength', value: 100}
        ])
        .addField('#passconf', [
            {rule: 'required'},
            {
                validator: (value, fields) => {
                    if (
                        fields['#password'] &&
                        fields['#password'].elem
                    ) {
                        const repeatPasswordValue =
                            fields['#password'].elem.value;
                        return value === repeatPasswordValue;
                    }
                    return true;
                },
                errorMessage: 'Passwords should be the same',
            },
        ])
        .addField('#email', [
            {rule: 'email'},
            {rule: 'maxLength', value: 100}
        ])
        .addField('#phone', [
            {rule: 'required'},
            {rule: 'minLength', value: 9}, {rule: 'maxLength', value: 18},
            {
                rule: 'customRegexp',
                value: /^(?:\+?(?!0)\d{1,3}[ .-]?)?(?:\d{2}[ .-]?)?\d{3}[ .-]?\d{3}[ .-]?\d{3,4}$/,
                errorMessage: 'Invalid phone number.'
            }
        ])
        .addField('#user_role', [{rule: 'required'}])
        .addField('#active', [{rule: 'required'}])
        .onSuccess((e) => {
            e.preventDefault();
            submitRegisterForm();
        });
}
function showRegisterAjaxError(data){
    try {
        if (Object.keys(data).length > 0) {
            $.each(data, function (index, value) {
                let elementId = `#${index}`;
                console.log(elementId)
                registerFormValidate.showErrors({[elementId]: value})
            });
        }
    }catch (e) {
        console.log(e)
        console.log(e.message)
        errorMessage = 'An error occurred. Please try again later.';
        showError('Error!', errorMessage);
    }
}

//Update
function updateUserForm(){
    console.log('update');
    let form = document.querySelector('#updateUserForm');
    let formData = $(form).serializeArray();
    console.log(formData);
    $.ajax({
        type: "POST",
        url: baseUrl + "admin/api/user/update",
        data: $('#updateUserForm').serialize(),
        dataType: 'json',
        beforeSend: function() { showLoading() },
        success: function (response) {
            console.log(response);
            hideLoading()
            if(response.status) {
                if (response.data.action === 'redirect') {
                    window.location.href = baseUrl + response.data.url;
                }
                showSuccess('Success', 'User updated successfully!')
                userTable.ajax.reload();
                $("#updateUserModal").modal('hide');
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
if ($('#updateUserForm').length) {
    updateFormValidate = new JustValidate('#updateUserForm', {
        validateBeforeSubmitting: true,
        errorFieldCssClass: ['invalid-field'],
        successFieldCssClass: ['valid-field'],
        errorLabelCssClass: ['invalid-label'],
        successLabelCssClass: ['valid-label']
    });

    updateFormValidate
        .addField('#up_first_name', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#up_last_name', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#up_gender', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        //.addRequiredGroup(document.querySelector('#up_radio-group'), 'Please select gender')
        .addField('#up_username', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#up_password', [
            {rule: 'minLength', value: 8, errorMessage: 'Password Must be at least 8 characters long'},
            {rule: 'maxLength', value: 100}
        ])
        .addField('#up_passconf', [
            {
                validator: (value, fields) => {
                    if (
                        fields['#up_password'] &&
                        fields['#up_password'].elem
                    ) {
                        const repeatPasswordValue =
                            fields['#up_password'].elem.value;
                        return value === repeatPasswordValue;
                    }
                    return true;
                },
                errorMessage: 'Passwords should be the same',
            },
        ])
        .addField('#up_email', [
            {rule: 'email'},
            {rule: 'maxLength', value: 100}
        ])
        .addField('#up_phone', [
            {rule: 'required'},
            {rule: 'minLength', value: 9}, {rule: 'maxLength', value: 18},
            {
                rule: 'customRegexp',
                value: /^(?:\+?(?!0)\d{1,3}[ .-]?)?(?:\d{2}[ .-]?)?\d{3}[ .-]?\d{3}[ .-]?\d{3,4}$/,
                errorMessage: 'Invalid phone number.'
            }
        ])
        .addField('#up_user_role', [{rule: 'required'}])
        .addField('#up_active', [{rule: 'required'}])
        .onSuccess((e) => {
            e.preventDefault();
            updateUserForm();
        });
}


function viewMember(id){
    updateFormValidate.refresh();
    clearForm();
    $('#user_id').val(id);
    $.ajax({
        type: "POST",
        url: baseUrl + "admin/api/user/get/data",
        data: {
            csrf_test_name: tk,
            user_id:id
        },
        dataType: 'json',
        beforeSend: function() { showLoading() },
        success: function (response) {
            console.log(response);
            hideLoading()
            console.log(response.data.action);
            if (response.status) {
                $('#up_first_name').val(response.data.first_name);
                $('#up_last_name').val(response.data.last_name);
                $('#up_username').val(response.data.username);
                $('#up_email').val(response.data.email);
                $('#up_specialization').val(response.data.specialization);
                $('#up_phone').val(response.data.phone);
                $('#up_gender').val(response.data.gender);
                $('#up_user_role').val(response.data.role_id);
                $('#up_active').val(response.data.active);
                updateFormValidate.revalidate().then(isValid => {})
                $("#updateUserModal").modal('show');

            } else {
                if (response.data.action === 'redirect') {
                    window.location.href = baseUrl + response.data.url;
                } else if (response.data.action === 'error') {
                    showUpdateAjaxError(response.data.error_data)
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
function deleteMember(id){
    $('#confirmDeleteModal').modal('show');
    $('#delete_user_id').val(id);
}
$('#confirmDeleteCancelBtn').click(function (){
    $('#delete_user_id').val('')
    $('#confirmDeleteModal').modal('hide');
});
$('#confirmDeleteBtn').click(function() {
    $('#confirmDeleteModal').modal('hide');
    let user_id = $('#delete_user_id').val();
    if(user_id) {
        $.ajax({
            type: "POST",
            url: baseUrl + "admin/api/user/delete",
            data: {
                csrf_test_name: tk,
                user_id: user_id
            },
            dataType: 'json',
            beforeSend: function () {
                showLoading()
            },
            success: function (response) {
                hideLoading()
                if (response.status) {
                    userTable.ajax.reload();
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


$('#addUser').click(function (){
    clearForm();
    //ReCaptchaCallbackV3();
    registerFormValidate.refresh();
    $("#addUserModal").modal('show');
})

$('#btnAddUserCancel').click(function() {
    clearForm()
    $("#addUserModal").modal('hide');
});

$('#btnUpdateUserCancel').click(function() {
    clearForm()
    $("#updateUserModal").modal('hide');
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