<?php

if (isset($_POST['submit'])) {
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $types = array("image/png", "image/jpg", "image/jpeg");
        $file_type = $_FILES['image']['type'];

        if (in_array($file_type, $types)) {
            $target_dir = "image/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $thumbnail_file = $target_dir . "thumbnail_" . basename($_FILES["image"]["name"]);

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                list($width, $height) = getimagesize($target_file);
                $new_width = 100;
                $new_height = 100;
                $thumb = imagecreatetruecolor($new_width, $new_height);

                if ($file_type == 'image/png') {
                    $source = imagecreatefrompng($target_file);
                } elseif ($file_type == 'image/jpg') {
                    $source = imagecreatefromjpg($target_file);
                }elseif ($file_type == 'image/jpeg') {
                    $source = imagecreatefromjpeg($target_file);
                }

                imagecopyresized($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                imagepng($thumb, $thumbnail_file);
?>
                <h2>Original Image</h2>
                <img src='<?= $target_file?>' alt='Original Image'><br><br>
                <h2>Thumbnail</h2>
                <img src='<?=$thumbnail_file?>' alt='Thumbnail'>
<?php
                imagedestroy($source);
                imagedestroy($thumb);
            } else {
                echo "Error";
            }
        }
    } else {
        echo "Error";
    }
}

