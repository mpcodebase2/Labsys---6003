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
        url: baseUrl + "api/appointment/create",
        data: $('#createAppointmentsForm').serialize(),
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
                showSuccess('Success', 'Appointment created successfully!')
                appointmentTable.ajax.reload();
                clearForm();
                $("#createAppointmentModal").modal('hide');
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
if ($('#createAppointmentsForm').length) {
    createFormValidate = new JustValidate('#createAppointmentsForm', {
        validateBeforeSubmitting: true,
        errorFieldCssClass: ['invalid-field'],
        successFieldCssClass: ['valid-field'],
        errorLabelCssClass: ['invalid-label'],
        successLabelCssClass: ['valid-label']
    });

    createFormValidate
        .addField('#test_id', [{rule: 'required'}, {rule: 'maxLength', value: 100}])
        .addField('#note', [{rule: 'maxLength', value: 400}])
        .addField('#amount', [{rule: 'required'},{rule: 'number'},{rule: 'maxLength', value: 50}])
        .addField('#paid', [{rule: 'required'},{rule: 'number'},{rule: 'maxLength', value: 50}])
        .addField('#due', [{rule: 'required'},{rule: 'number'},{rule: 'maxLength', value: 50}])
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

function viewAppointments(id){
    clearForm(); $('#test_result_media').empty();
    $('#appointment_id').val(id);
    $.ajax({
        type: "POST",
        url: baseUrl + "admin/api/appointment/get/data",
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
                $('#appointment_id').text(response.data.id);
                $('#test').text(response.data.TestName);
                $('#doctor').text(response.data.doctor);
                $('#appointment_date').text(response.data.appointment_date);
                $('#note').text(response.data.note);
                $('#amount').text(response.data.amount);
                $('#paid').text(response.data.paid);
                $('#due').text(response.data.due);
                displayMediaFiles(response.data.media_files)

                $("#updateAppointmentModal").modal('show');
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

$('#btnUpdateCancel').click(function() {
    clearForm()
    $("#updateAppointmentModal").modal('hide');
});
function displayMediaFiles(mediaFiles) {
    // Clear the existing content in the test_result_media div
    $('#test_result_media').empty();

    // Check if mediaFiles exists and is not empty
    if (mediaFiles && mediaFiles.length > 0) {
        // Iterate through each file in the mediaFiles array
        mediaFiles.forEach(function(file) {
            let previewElement = $('<div class="media-preview"></div>');

            // Check if the file is an image
            if (file.file_type.includes('image')) {
                // If it's an image, create an <img> element for preview
                let imgPreview = $('<img class="media-preview-icon" src="' + file.path + '" alt="' + file.file_name + '">');
                previewElement.append(imgPreview);
                let downloadLink = $('<a class="download-media-btn" href="' + file.path + '" download="' + file.file_name + '"><img src="'+baseUrl+'assets/images/download.png" class="donwload-icon" alt="kk"></a>');
                previewElement.append(downloadLink);
                // let previewBtn = $('<button class="preview-media-btn" data-image="' + file.path + '"><i data-feather="eye"></i></button>');
                // previewElement.append(previewBtn);
                previewElement.attr('data-type', 'image');
                previewElement.attr('data-file', file.path);

            } else {
                let pdfImage = baseUrl+'assets/images/pdf.jpg'; let otherImage = baseUrl+'assets/images/other.jpg';
                let image = otherImage; let fileType = 'other';
                if (file.file_type.includes('pdf')){
                    image = pdfImage; fileType= 'pdf';
                }
                let imgPreview = $('<img class="media-preview-icon" src="' + image + '" alt="media">');
                previewElement.append(imgPreview);
                let downloadLink = $('<a class="download-media-btn" href="' + file.path + '" download="' + file.file_name + '"><img src="'+baseUrl+'assets/images/download.png" class="donwload-icon" alt="kk"></a>');
                previewElement.append(downloadLink);
                // let previewBtn = $('<button class="preview-media-btn" data-type="'+fileType+'" data-image="' + file.path + '"><i data-feather="eye"></i></button>');
                // previewElement.append(previewBtn);
                previewElement.attr('data-type', fileType);
                previewElement.attr('data-file', file.path);
            }

            // Append the preview element to the test_result_media div
            $('#test_result_media').append(previewElement);

            // Open file in modal on click (you need to implement this functionality)
            previewElement.on('click', function() {
                // Implement modal functionality to open the file
                // You can use a library like Bootstrap modal or implement a custom modal
            });
        });
    } else {
        // Handle the case when mediaFiles is empty or undefined
        $('#test_result_media').html('<p>No media files found.</p>');
    }
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



$(document).ready(function(){
    // Function to fetch test amount by test ID
    function fetchTestAmount(testId) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: baseUrl + '/admin/api/labtest/get/data', // Replace with the actual URL for fetching test amount
                type: 'POST',
                data: {id: testId},
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    if(response.status) {
                        console.log(response.data.cost)
                        resolve(response.data.cost);
                    } else {
                        reject('Error: Unable to fetch test amount.');
                    }
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    function updateDue() {
        let amount = parseFloat($('#amount').val()) || 0;
        let paid = parseFloat($('#paid').val()) || 0;
        let due = amount - paid;
        $('#due').val(due.toFixed(2));
    }

    $('#amount, #paid').on('input', updateDue);

    $('#test_id').change(function(){
        let testId = $(this).val();
        fetchTestAmount(testId)
            .then(function(amount) {
                $('#amount').val(amount);
                updateDue();
            })
            .catch(function(error) {
                console.error(error);
            });
    });

    $('#amount, #paid').on('input', function() {
        let amount = parseFloat($('#amount').val()) || 0;
        let paid = parseFloat($('#paid').val()) || 0;
        let due = amount - paid;
        $('#due').val(due.toFixed(2));
    });
});


$(document).on('click', '.media-preview', function() {
    let mediaType = $(this).data('type');
    let file = $(this).data('file');
    $('#preview-link-frame').empty();
    if (mediaType === 'pdf') {
        let pdfPreview = $('<iframe>').attr({
            src: file,
            width: '100%',
            height: '100%',
            frameborder: '0'
        });
        $('#preview-link-frame').append(pdfPreview);
    } else if (mediaType.includes('image')) {
        let imgPreview = $('<img>').attr({
            src: file,
            alt: 'Preview',
            style: 'width: 100%; height: auto;'
        });
        $('#preview-link-frame').append(imgPreview);
    }
    $('#previewModal').modal('show');
});
