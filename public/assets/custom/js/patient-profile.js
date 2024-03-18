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
        url: baseUrl + "api/patient-profile/create",
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
