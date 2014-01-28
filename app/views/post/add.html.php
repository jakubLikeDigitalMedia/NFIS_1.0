<?php $controller->getModel()->loadForm('add', $vars); ?>
<button class="btn btn-default" name="add_media" id="add_media">Add media</button>
<fieldset id="upload_media">
    <legend>Add media</legend>
    <div class="wrapper">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="media_tabs">
          <li><a href="#images_tab" data-toggle="tab">Upload Images</a></li>
          <li><a href="#videos_tab" data-toggle="tab">Upload Videos</a></li>
          <li><a href="#gallery_tab" data-toggle="tab">Image Gallery</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active fade in" id="images_tab">
                <form method="post" action="<?php echo $controller->vars['imageActionLink']; ?>" enctype="multipart/form-data">
                    <div id="queue"></div>
                    <input class="btn btn-default" name="Filedata" id="post_image_upload" type="file" multiple="multiple">
                    <input type="submit" id="image_submit" />
                    <ul>
                        <li></li>
                    </ul>
                </form>

                <fieldset id="crop_image">
                    <legend>Crop Image</legend>
                    <div id="images_to_crop"></div>
                </fieldset>
            </div>
          <div class="tab-pane fade" id="videos_tab">
              <label>Insert embeded code <textarea id="embeded_video_code"></textarea></label>
              <input type="submit" id="video_submit" />
          </div>
            <div class="tab-pane fade" id="gallery_tab">
                <ul>
                   <?php 
                        foreach($controller->images as $id => $value){
                            $imgName = $value['name'];
                            $imgOrgName = $value['original_name'];
                            $imgUploadedBy = $value['uploaded_by'];
                            $imgId = $id;
                            echo '<li><a href="' . UNSECURE_URL . '/public/images/employee/original/' . $imgName . '" data-lightbox="roadtrip" title=""><img id="'.$imgId.'" src="' . UNSECURE_URL . '/public/images/employee/thumb/gallery/' . $imgName . '" /></a></li>';
                        }
                   ?>
                </ul>
            </div>
        </div>
    </div>
</fieldset>
