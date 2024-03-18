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
        url: baseUrl + "admin/api/patient/create",
        data: $('#createPatientsForm').serialize(),
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
                showSuccess('Success', 'Patient created successfully!')
                patientTable.ajax.reload();
                clearForm();
                $("#createPatientModal").modal('hide');
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
if ($('#createPatientsForm').length) {
    createFormValidate = new JustValidate('#createPatientsForm', {
        validateBeforeSubmitting: true,
        errorFieldCssClass: ['invalid-field'],
        successFieldCssClass: ['valid-field'],
        errorLabelCssClass: ['invalid-label'],
        successLabelCssClass: ['valid-label']
    });

    createFormValidate
        .addField('#email', [{rule: 'required'}, {rule: 'email'}])
        .addField('#password', [
            // {rule: 'required'},
            {rule: 'minLength', value: 8, errorMessage: 'Password Must be at least 8 characters long'},
            {rule: 'maxLength', value: 100}
        ])
        .addField('#nic', [
            {rule: 'required'},
            // {rule: 'minLength', value: 10},
            {rule: 'maxLength', value: 12},
            {
                rule: 'customRegexp',
                value: /^(?:19|20)?\d{2}[0-9]{10}|[0-9]{9}[x|X|v|V]$/,
                errorMessage: 'Invalid NIC number.'
            }
        ])
        .addField('#first_name', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#last_name', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#gender', [{rule: 'required'}])
        .addField('#dob', [
            {rule: 'required'},
            {
                plugin: JustValidatePluginDate(() => ({
                    format: 'yyyy-mm-dd',
                })),
                errorMessage: 'Date should be in yyyy-mm-dd format (e.g. 1985-03-03)',
            },
        ])
        .addField('#address_ln1', [{rule: 'required'}, {rule: 'maxLength', value: 255}])
        .addField('#address_ln2', [{rule: 'maxLength', value: 255}])
        .addField('#city', [{rule: 'required'}])
        .addField('#district', [{rule: 'required'}])
        .addField('#province', [{rule: 'required'}])
        .addField('#country', [{rule: 'required'}])
        .addField('#telephone', [
            {rule: 'required'},
            {rule: 'minLength', value: 9}, {rule: 'maxLength', value: 18},
            {
                rule: 'customRegexp',
                value: /^(?:\+?(?!0)\d{1,3}[ .-]?)?(?:\d{2}[ .-]?)?\d{3}[ .-]?\d{3}[ .-]?\d{3,4}$/,
                errorMessage: 'Invalid phone number.'
            }
        ])
        .addField('#mobile', [
            {rule: 'minLength', value: 9}, {rule: 'maxLength', value: 18},
            {
                rule: 'customRegexp',
                value: /^(?:\+?(?!0)\d{1,3}[ .-]?)?(?:\d{2}[ .-]?)?\d{3}[ .-]?\d{3}[ .-]?\d{3,4}$/,
                errorMessage: 'Invalid phone number.'
            }
        ])
        .addField('#occupation', [{rule: 'maxLength', value: 100}])
        // .addField('#religion', [{rule: 'required'}])
        // .addField('#nationality', [{rule: 'required'},])
        .addField('#is_active', [{rule: 'required'}])
        // .addField('#is_staff', [{rule: 'required'}])
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
    let form = document.querySelector('#updatePatientForm');
    let formData = $(form).serializeArray();
    console.log(formData);
    $.ajax({
        type: "POST",
        url: baseUrl + "admin/api/patient/update",
        data: $('#updatePatientForm').serialize(),
        dataType: 'json',
        beforeSend: function() { showLoading() },
        success: function (response) {
            console.log(response);
            hideLoading()
            if(response.status) {
                if (response.data.action === 'redirect') {
                    window.location.href = baseUrl + response.data.url;
                }
                showSuccess('Success', 'Patient updated successfully!')
                patientTable.ajax.reload();
                $("#updatePatientModal").modal('hide');
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
if ($('#updatePatientForm').length) {
    updateFormValidate = new JustValidate('#updatePatientForm', {
        validateBeforeSubmitting: true,
        errorFieldCssClass: ['invalid-field'],
        successFieldCssClass: ['valid-field'],
        errorLabelCssClass: ['invalid-label'],
        successLabelCssClass: ['valid-label']
    });

    updateFormValidate
        .addField('#up_email', [{rule: 'required'}, {rule: 'email'}])
        .addField('#up_password', [
            {rule: 'minLength', value: 8, errorMessage: 'Password Must be at least 8 characters long'},
            {rule: 'maxLength', value: 100}
        ])
        .addField('#up_nic', [
            {rule: 'required'},
            // {rule: 'minLength', value: 10},
            {rule: 'maxLength', value: 12},
            {
                rule: 'customRegexp',
                value: /^(?:19|20)?\d{2}[0-9]{10}|[0-9]{9}[x|X|v|V]$/,
                errorMessage: 'Invalid NIC number.'
            }
        ])
        .addField('#up_first_name', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#up_last_name', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#up_gender', [{rule: 'required'}])
        .addField('#up_dob', [
            // {rule: 'required'},
            {
                plugin: JustValidatePluginDate(() => ({
                    format: 'yyyy-mm-dd',
                })),
                errorMessage: 'Date should be in yyyy-mm-dd format (e.g. 1985-03-03)',
            },
        ])
        .addField('#up_address_ln1', [{rule: 'required'}, {rule: 'maxLength', value: 255}])
        .addField('#up_address_ln2', [{rule: 'maxLength', value: 255}])
        .addField('#up_city', [{rule: 'required'}])
        .addField('#up_district', [{rule: 'required'}])
        .addField('#up_province', [{rule: 'required'}])
        .addField('#up_country', [{rule: 'required'}])
        .addField('#up_telephone', [
            {rule: 'required'},
            {rule: 'minLength', value: 9}, {rule: 'maxLength', value: 18},
            {
                rule: 'customRegexp',
                value: /^(?:\+?(?!0)\d{1,3}[ .-]?)?(?:\d{2}[ .-]?)?\d{3}[ .-]?\d{3}[ .-]?\d{3,4}$/,
                errorMessage: 'Invalid phone number.'
            }
        ])
        .addField('#up_mobile', [
            {rule: 'minLength', value: 9}, {rule: 'maxLength', value: 18},
            {
                rule: 'customRegexp',
                value: /^(?:\+?(?!0)\d{1,3}[ .-]?)?(?:\d{2}[ .-]?)?\d{3}[ .-]?\d{3}[ .-]?\d{3,4}$/,
                errorMessage: 'Invalid phone number.'
            }
        ])
        .addField('#up_occupation', [{rule: 'maxLength', value: 100}])
        .addField('#up_religion', [{rule: 'required'}])
        .addField('#up_nationality', [{rule: 'required'},])
        .addField('#up_is_active', [{rule: 'required'}])
        .addField('#up_is_staff', [{rule: 'required'}])
        .onSuccess((e) => {
            e.preventDefault();
            updateForm();
        });
}
function viewPatients(id){
    updateFormValidate.refresh();
    clearForm();
    $('#user_id').val(id);
    $.ajax({
        type: "POST",
        url: baseUrl + "admin/api/patient/get/data",
        data: {
            csrf_test_name: tk,
            id:id
        },
        dataType: 'json',
        beforeSend: function() { showLoading() },
        success: function (response) {
            console.log(response)
            hideLoading()
            if (response.status) {
                $('#up_email').val(response.data.email);
                $('#up_nic').val(response.data.nic);
                $('#up_first_name').val(response.data.first_name);
                $('#up_last_name').val(response.data.last_name);
                $('#up_gender').val(response.data.gender);
                $('#up_dob').val(response.data.dob);
                $('#up_address_ln1').val(response.data.address_ln1);
                $('#up_address_ln2').val(response.data.address_ln2);
                $('#up_city').val(response.data.city);
                $('#up_district').val(response.data.district);
                $('#up_province').val(response.data.province);
                $('#up_country').val(response.data.country);
                $('#up_telephone').val(response.data.telephone);
                $('#up_mobile').val(response.data.mobile);
                $('#up_occupation').val(response.data.occupation);
                $('#up_religion').val(response.data.religion);
                $('#up_nationality').val(response.data.nationality);
                $('#up_is_active').val(response.data.is_active);
                $('#up_is_staff').val(response.data.is_staff);
                $('#patient_id').val(response.data.id);
                updateFormValidate.revalidate().then(isValid => {});
                $("#updatePatientModal").modal('show');
            }else {
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

function deletePatients(id){
    $('#confirmDeleteModal').modal('show');
    $('#delete_patient_id').val(id);
}
$('#confirmDeleteCancelBtn').click(function (){
    $('#delete_patient_id').val('')
    $('#confirmDeleteModal').modal('hide');
});
$('#confirmDeleteBtn').click(function() {
    $('#confirmDeleteModal').modal('hide');
    let id = $('#delete_patient_id').val();
    if(id) {
        $.ajax({
            type: "POST",
            url: baseUrl + "admin/api/patient/delete",
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
                    $('#delete_patient_id').val('')
                    patientTable.ajax.reload();
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

$('#btnCreatePatient').click(function (){
    clearForm();
    createFormValidate.refresh();
    $("#createPatientModal").modal('show');
})

$('#btnCreateCancel').click(function() {
    clearForm()
    $("#createPatientModal").modal('hide');
});

$('#btnUpdateCancel').click(function() {
    clearForm()
    $("#updatePatientModal").modal('hide');
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