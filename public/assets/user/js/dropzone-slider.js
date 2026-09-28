
"use strict";
// myDropzone is the configuration for the element that has an id attribute
// with the value my-dropzone (or myDropzone)
Dropzone.options.myDropzone = {
    acceptedFiles: '.png, .jpg, .jpeg, .webp, .svg, .jfif, .avif, .PNG, .JPG, .JPEG, .WEBP, .SVG',
    url: uploadSliderImage,
    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '' },
    success: function (file, response) {
        // Guard: response must be an object with a valid file_id string
        if (!response || typeof response !== 'object' || !response.file_id || typeof response.file_id !== 'string') {
            this.removeFile(file);
            alert('Upload failed: unexpected server response. Please try again.');
            return;
        }
        if (response.status === 'error') {
            this.removeFile(file);
            var errMsg = (response.errors && response.errors.file) ? response.errors.file[0] : 'Upload failed.';
            alert(errMsg);
            return;
        }
        $("#sliders").append(`<input type="hidden" name="image[]" id="slider${response.file_id}" value="${response.file_id}">`);
        // Create the remove button
        var removeButton = Dropzone.createElement("<button class='btn btn-xs rmv-btn'><i class='fa fa-times'></i></button>");
        // Capture the Dropzone instance as closure.
        var _this = this;
        var fileId = response.file_id;
        // Listen to the click event
        removeButton.addEventListener("click", function (e) {
            // Make sure the button click doesn't submit the form:
            e.preventDefault();
            e.stopPropagation();
            _this.removeFile(file);
            rmvImg(fileId);
        });
        // Add the button to the file preview element.
        file.previewElement.appendChild(removeButton);
    },
    error: function (file, message, xhr) {
        // Remove the broken preview from dropzone UI
        this.removeFile(file);
        var errText = 'Slider image upload failed.';
        if (typeof message === 'string' && message.length < 200) {
            errText = message;
        } else if (xhr && xhr.status === 500) {
            errText = 'Server error (500) while uploading slider image. Please check server logs.';
        } else if (xhr && xhr.status === 422) {
            try {
                var resp = JSON.parse(xhr.responseText);
                if (resp && resp.errors) {
                    var msgs = [];
                    for (var k in resp.errors) { msgs.push(resp.errors[k][0]); }
                    errText = msgs.join(' ');
                }
            } catch(e) {}
        }
        alert(errText);
    }
};

function rmvImg(file_Id) {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    $.ajax({
        url: rmvSliderImage,
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { 'value': file_Id, '_token': csrf },
        success: function (data) {
            const ele = document.getElementById("slider" + file_Id);
            ele.remove();
        },
        error: function (e) {
        }
    });
}

function rmvdbimg(key, id) {
    $(".request-loader").addClass("show");
    $.ajax({
        url: rmvDbSliderImage,
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            key: key,
            id: id
        },
        success: function (data) {
            $(".request-loader").removeClass("show");
            var content = {};
            if (data == 'success') {
                $("#trdb" + key).remove();
                content.message = 'Slider image deleted successfully!';
                content.title = 'Success';
                var type = 'success';
            } else {
                content.message = "You can't delete all images";
                content.title = 'Success';
                var type = 'warning';
            }
            content.icon = 'fa fa-bell';

            $.notify(content, {
                type: type,
                placement: {
                    from: 'top',
                    align: 'right'
                },
                showProgressbar: true,
                time: 1000,
                delay: 4000,
            });
        }
    });
}
