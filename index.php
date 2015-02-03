<!DOCTYPE html>
<html>
    <head>
        <link  rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link  rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <header>

        </header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="file-upload">File input</label>
<!--                            <input id="file-upload" multiple="multiple" name="file" type="file" />-->
                            <input id="file-upload" name="picture" type="file" />
                            <p class="help-block">Choose an image to upload</p>
                            <p class="error"></p>
                        </div>

                        <input type="button" value="Upload" />
                        <input type="submit" value="Upload" />
                    </form>
                    <progress id="myProgress" class="progress" value="" max=""></progress>
                </div>
            </div>
            <div class="row">
                <div class="original text-center col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h3>Original</h3>
                </div>
                <div class="gd_lib text-center col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h3>GD Library</h3>
                </div>
                <div class="img_mag text-center col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h3>Image Magick</h3>
                </div>
                <div class="tiny_png text-center col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h3>Tiny PNG</h3>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script>

        var maxFileSize = 10240000;
        var validFileSize = ['image/jpeg', 'image/jpg', 'image/png'];

        $(':file').change(function(){
            console.log(this.files);
            var file = this.files[0];

            var fileName = file.name;
            var fileSize = file.size;
            var fileType = file.type;


            /** Validate file size max 10mb*/
            if(fileSize > maxFileSize ){
                console.log();
                $('.error').text("Max file size is " + (maxFileSize/1024)/100 + "mb");
                return false;
            }

            /** Validate file type */
//            if($.inArray(fileType, validFileSize) < 0){
//                $('.error').text("Invalid file type");
//                return false;
//            }


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
            console.log('before send handler');
        }

        function completeHandler(data){
            console.log(data);
            console.log('complete handler');

            for(var d in data){
                $('.' + d).append("<img src='"+window.location.href + 'uploads/' + data[d]['fileName']+"' />");
//                var url = encodeURI(window.location.href + 'uploads/' + data[d]['fileName']);
//                $('.' + d).attr('src', window.location.href + 'uploads/' + data[d]['fileName']);
            }

            $(':file').val('');
        }

        function errorHandler(){
            console.log('error handler');
        }

        function progressHandlingFunction(e){
            if(e.lengthComputable){
                $('progress').attr({value:e.loaded,max:e.total});
            }
        }
    </script>
</html>