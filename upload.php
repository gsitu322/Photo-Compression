<?php

/** Initiates our directory variable and accepted file types */
$uploadDirectory = getcwd() . "/uploads/";
$destinationFile = null;
$acceptedFileTypes = array('image/png');
define('GD_LIB_PRE' , 'gd_lib_');
define('IMG_MAG_PRE' , 'img_mag_');
define('TINY_PNG_PRE' , 'tiny_png_');

try{
    /** Check if this is a post request */
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        /** Check if there is an image and if the image type is a PNG */
        if(!is_null($_FILES['picture']) && in_array($_FILES['picture']['type'], $acceptedFileTypes)){

            try{
                /** Move image to server directory */
                $destinationFile = $uploadDirectory . basename($_FILES['picture']['name']);
                $fileName = $_FILES['picture']['name'];
                move_uploaded_file($_FILES['picture']['tmp_name'], $destinationFile);

                /** Compress with GD Library, ImageMagicK, and TinyPng. Upload the processed image to the uploads directory */
                compressWithGDLib($destinationFile, $uploadDirectory);
                compressWithImageMaigicK($destinationFile, $uploadDirectory);
                compressWithTinyPng($destinationFile, $uploadDirectory);

                /** Calculates the image statistic for each image and then sends back to the the front end JSON data */
                echo json_encode(array_merge(getImageStatistics('original', $destinationFile), getImageStatistics('gd_lib', $uploadDirectory . GD_LIB_PRE . $fileName), getImageStatistics('img_mag', $uploadDirectory . IMG_MAG_PRE . $fileName), getImageStatistics('tiny_png', $uploadDirectory . TINY_PNG_PRE . $fileName)));

            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }else{
            throw new Exception('Upload type is not PNG');
        }
    }else{
        throw new Exception('Request is not POST');
    }
}catch (Exception $e){
    echo json_encode(array('error' => $e->getMessage()));
}

/**
 * @param $destinationFile
 * @param $uploadDirectory
 * @throws Exception
 *
 * Compress our image with the GD Library and save to the uploads directory. Throws an error if the file is unable to be processed or saved
 */
function compressWithGDLib($destinationFile, $uploadDirectory){
    try{
        $fileName = basename($destinationFile);

        /** @var $image
         * Create image and  process with GD Library
         */
        $image = ImageCreateFromPNG($destinationFile);
        imagealphablending($image, false);
        imagesavealpha($image, GD_LIB_PRE . $fileName);

        /** Save image to uploads directory  */
        imagepng($image, $uploadDirectory . GD_LIB_PRE . $fileName, 1, 'PNG_ALL_FILTERS');
    }catch (Exception $e){
        throw new Exception($e->getMessage());
    }

    return;
}

/**
 * @param $destinationFile
 * @param $uploadDirectory
 * @throws Exception
 *
 * Compress the image with ImageMagicK. After compression is complete the image is saved to the uploads directory.
 */
function compressWithImageMaigicK($destinationFile, $uploadDirectory){
    $fileName = basename($destinationFile);

    try{
        /** @var Imagick $imImage Create the ImageMagick wand */
        $imImage = new Imagick($destinationFile);
        $imageId = $imImage->identifyImage(true);

        /** Using the image information to find the correct color space. We need this because it will help preserve the correct colors */
        $colorSpace = constant(strtoupper('Imagick::COLORSPACE_' . $imageId['colorSpace']));

        /** Use histogram to find all the colors and error log the number of colors there are*/
        /** TODO Need to find a better way to optimize the number of colors to get */
        //$instruction = "convert ". $destinationFile . " -format %c  -depth 8  histogram:info:-";
        //$result =explode('        ',shell_exec($instruction));
        //$numbColors = 0;
        //foreach($result as $a){
        //    preg_match('/(\d*)?(\:)/', $a, $matches);
        //    if($matches[1] > 30){
        //        $numbColors++;
        //    }
        //}

        /** Quantize Image is our main tool to compress the image. Quantize image reduces the numbers of colors to a smaller subset resulting in an image that is smaller in file size. Currently quantizeImage reduces the colors of the image to 256 colors */
        /** TODO find way to fix dither errors */
        $imImage->quantizeImage(256, $colorSpace , 0, false, false);

        /** @var Imagick $ns Create color palette for remapping the colors to the image to the colors on the palette. */
        /** TODO Find way to incoporate this for possibly gif use */
        //$ns = new Imagick('colors/colormap_332.png');
        //$imImage->remapImage($ns, Imagick::DITHERMETHOD_FLOYDSTEINBERG  );

        /** Write the wand image to the uploads directory */
        $imImage->writeImage($uploadDirectory . IMG_MAG_PRE . $fileName);

    }catch (Exception $e){
        throw new Exception($e->getMessage());
    }

    return;
}

/**
 * @param $destinationFile
 * @param $uploadDirectory
 * @throws Exception
 *
 * Compress the image with tinypng.com. After the compression the file is saved to the uploads directory. Throws an error if there is a problem with tiny png or if the file cannot be saved
 */
function compressWithTinyPng($destinationFile, $uploadDirectory){

    $fileName = basename($destinationFile);

    try{
        /** Compress image with TinyPNG */
        $tinyPngJSON = exec("curl -i --user api:c4DHyr4OeSwMkxnAoXpPSoRvRFxOt8wQ --data-binary ". escapeshellarg('@uploads/'. $fileName) ."  https://api.tinypng.com/shrink");
        $tinyPngData = json_decode($tinyPngJSON);

        /** Save the tinyPNG image to the server */
        $url = $tinyPngData->output->url;
        $img = $uploadDirectory . TINY_PNG_PRE . $fileName;
        file_put_contents($img, file_get_contents($url));
    }catch (Exception $e){
        throw new Exception($e->getMessage());
    }

    return;
}

/** Process the image statistics of the image */

/**
 * @param $fileCat
 * @param $filePath
 * @return array
 * @throws Exception
 *
 * Process our image and gets image statistics. Throws an error if file is unable to get image statistics.
 */
function getImageStatistics($fileCat, $filePath){
    $fileStats = array();

    try{
        $imageSize = getimagesize($filePath);
        $fileSize = filesize($filePath);
        $fileName = basename($filePath);

        $fileStats[$fileCat] = array(
            'width' => $imageSize[0],
            'height' => $imageSize[1],
            'type' => $imageSize['mime'],
            'filesize' => $fileSize,
            'fileName' => $fileName
        );
    }catch (Exception $e){
        throw new Exception($e->getMessage());
    }

    return $fileStats;
}