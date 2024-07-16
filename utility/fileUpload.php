<?php

function fileUpload($file)
{

    $uploadDir =  basePath('uploads/');

    $target_file = $uploadDir . basename($file["file"]["name"]);

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($file["file"]["size"] > 500000) {
        loadView(
            'component/notification',
            [
                'message' => 'File is too big.',
                'type' => 'error'
            ]
        );
        $uploadOk = 0;
        return;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }



    if (move_uploaded_file($file["file"]["tmp_name"], $target_file)) {
        $img_url = $target_file;
        return $img_url;
    } else {
        loadView(
            'component/notification',
            [
                'message' => 'There was an error with the file',
                'type' => 'error'
            ]
        );
    }
}
