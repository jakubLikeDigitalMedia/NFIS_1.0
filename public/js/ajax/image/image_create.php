<?php
$image = new Image_Model();

function uploadImage($_files, $image){
    $newImageId = $image->uploadImage($_files);
    echo $image->userId . "," . $newImageId;
}

function cropImage($x, $y, $x2, $y2, $w, $h, $imageName, $image){
    $image->cropImage($x, $y, $x2, $y2, $w, $h, $imageName);
    echo '1';
}

function updateImageDesc($title, $desc, $newImgId, $image){
    $image->updateImageDesc($title, $desc, $newImgId);
    echo '1';
}

//upload image
if(!empty($_FILES) && (isset($_POST['ajax_page']) && !empty($_POST['ajax_page']))){
    uploadImage($_FILES, $image);
}

//crop image

if(!empty($_POST['crop_image']) && (isset($_POST['crop_image']) && $_POST['crop_image'] == "true")){
    cropImage($_POST['selectX'], $_POST['selectY'], $_POST['selectX2'], $_POST['selectY2'], $_POST['selectW'], $_POST['selectH'], $_POST['imageName'], $image);
}

//update image description and title
if(!empty($_POST['image_update']) && (isset($_POST['image_update']) && $_POST['image_update'] == "true")){
    updateImageDesc($_POST['image_title'], $_POST['image_desc'], $_POST['new_img_id'], $image);
}