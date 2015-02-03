/** initiate file size and valid file types */
var maxFileSize = 10240000;
var validFileTypes = ['image/png'];

/** upload when the file changes */
$(':file').change(function(){

    /** remove error text */
    $('.error').text('');

    /** parse file information */
    var file = this.files[0];
    var fileName = file.name;
    var fileSize = file.size;
    var fileType = file.type;


    /** Validate file size max 10mb*/
    if(fileSize > maxFileSize ){
        $('.error').text("Max file size is " + (maxFileSize/1024)/1000 + "mb");
        return false;
    }

    /** Validate file type */
    if($.inArray(fileType, validFileTypes) < 0){
        $('.error').text("Invalid file type");
        return false;
    }

    /** ajax to submit the form and image data to be prcoessed */
    var formData = new FormData($('form')[0]);
    $.ajax({
        url: 'upload.php',  //Server script to process data
        type: 'POST',
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){ // Check if upload property exists
                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
            }
            return myXhr;
        },
        beforeSend: beforeSendHandler,
        success: completeHandler,
        error: errorHandler,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json"
    });
});

function beforeSendHandler(){

}

/** when image compressions are complete append the image src on to the browser for viewing */
function completeHandler(data){
    for(var d in data){
        $('.' + d).append("<img src='"+window.location.href + 'uploads/' + data[d]['fileName']+"' />");
    }
    $(':file').val('');
}

function errorHandler(){
}

function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}