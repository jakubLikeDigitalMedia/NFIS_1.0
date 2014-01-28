<?php

function uploadVideo($_post_video){
    $video = new Video_Model();
    $video_code = array('content' => htmlspecialchars(addslashes($_post_video)));
    $video->insertVideo($video_code);
    echo '1';
}

if(isset($_POST['video_code']) && !empty($_POST['video_code'])){
    uploadVideo(($_POST['video_code']));
}