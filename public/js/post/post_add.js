$(function(){
     var rootPath = location.protocol + "//" + location.hostname;
      var loading = $('<img class="loading" src="'+rootPath + '/public/images/libs/lightbox/loading.gif" />');
    
    //add post page - tabs
    $('#myTab a:last').tab('show');
    $('#media_tabs a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });
    
    //show 'upload media' section
    $('#add_media').on('click', function(e){
        e.preventDefault();
        $('#upload_media').fadeIn();
    });
      
      var coords = 0;
      function getCoords(crds){
          coords = crds;
      }
      
      function CropInstance(imageId, imageName, newImgId){
          this.imageId = imageId;
          this.imageName = imageName;
          this.newImgId = newImgId;
      }
      CropInstance.prototype.getNewInstance = function(){
              var cropInstance = $('<div/>', {id: 'crop-'+this.imageId});
              var cropBtn =$('<button/>', {class: 'crop_btn', text: 'Crop', value: this.imageName});
              var descriptionBtn =$('<button/>', {class: 'add_description', text: 'Add info'});
              var setMainBtn =$('<button/>', {class: 'set_main_image', 'data-name': this.imageName, text: 'Set as main', disabled: 'disabled'});
              var addToPostBtn = $('<button/>', {class: 'add_to_post', id: 'add-'+this.imageId, text: 'ADD TO POST', 'data-name': this.imageName, disabled: 'disabled'});
              var cropImage = $('<img src="'+rootPath+'/public/images/employee/original/' + this.imageName+'" />');
              var titleInput = $('<label>Image title </label><input type="text" name="title-'+this.imageId+'" />');
              var descInput = $('<label>Image description </label><textarea name="desc-'+this.imageId+'"></textarea>');
              var descWrapper = $('<div/>', {class: 'desc-wrapper', style: 'display: none;', 'data-newimage': this.newImgId});
                cropImage.Jcrop({
                  setSelect:   [ 0, 0, 200, 200 ],
                  minSize: [200,200],
                  maxSize: [200,200],
                  boxWidth: 500,
                  boxHeight: 500,
                  onSelect: getCoords,
                  onChange: getCoords
              });
              descWrapper.append(titleInput).append(descInput);
              cropInstance.append(cropImage).append(cropBtn).append(descriptionBtn).append(setMainBtn).append(addToPostBtn).append(descWrapper);
              return cropInstance;
          }
      
      
      
      // add to post btn event
    $(document).on("click", ".add_image_to_post",function(e){
         e.preventDefault();
         var imageId = $(this).attr('id').split('init-')[1];
         var imageName = $(this).attr('data-name');
         var newImgId = $(this).attr('data-newimage');
         if($(this).hasClass('initialized')){
             $("#crop-"+imageId).fadeIn();
             $('<input class="hidden_img_info" type="hidden" name="input-'+imageId+'" id="'+imageName+'" value="input-'+imageName+'" />').prependTo('#add_post');
         }else{
            $(this).hide().addClass('initialized');
            var crpInstanceObj = new CropInstance(imageId,imageName, newImgId);
            var crpInstance = crpInstanceObj.getNewInstance();
            crpInstance.appendTo('#images_to_crop');
            $('#crop_image').fadeIn();
        }
     });
     
     //cropping an image after selecting the area
     
     $(document).on("click", ".crop_btn",function(e){
         e.preventDefault();
         $(this).parent().append(loading);
         var selectX = coords.x;
         var selectY = coords.y;
         var selectX2 = coords.x2;
         var selectY2 = coords.y2;
         var selectW = coords.w;
          var selectH = coords.h;
          var thisBtn = $(this);
          var imageName = thisBtn.val();
          
          var postData = "&selectX=" + selectX + "&selectY=" + selectY + "&selectX2=" + selectX2 + "&selectY2=" + selectY2 + "&selectW=" + selectW + "&selectH=" + selectH;
          postData += "&imageName="+imageName
          
          $.post(rootPath + "/public/js/ajax/ajax_config.php", "crop_image=true" + postData, function(data){
              if(data != '1'){
                  console.log("crop error: " + data);
                  thisBtn.parent().find('.loading').remove();
              }else{
                  thisBtn.parent().find('.tmp_crop').remove();
                  thisBtn.parent().append('<img class="tmp_crop" src="'+rootPath+'/public/images/employee/cropped/' + imageName + '" />');
                  thisBtn.siblings('.add_to_post').removeAttr('disabled');
                  thisBtn.parent().find('.loading').remove();
              }
          });
     });
     
     //adding image to post
     
     $(document).on("click", ".add_to_post",function(e){
         var imageId = $(this).attr('id').split('-')[2];
         var imageName = $(this).attr('data-name').split('.')[0];
         $('<input class="hidden_img_info" type="hidden" name="input-image-'+imageId+'" id="'+imageName+'" value="input-'+imageName+'" />').prependTo('#add_post');
         $(this).text('REMOVE FROM POST').removeClass('add_to_post').addClass('remove_from_post');
         $(this).siblings('.set_main_image').removeAttr('disabled');
         
         var title = $(this).parent().find('.desc-wrapper').find('input').val();
        var desc = $(this).parent().find('.desc-wrapper').find('textarea').val();
        var newImgId = $(this).parent().find('.desc-wrapper').attr('data-newimage');
        $.post(rootPath + "/public/js/ajax/ajax_config.php", "image_update=true"+"&image_title="+title+"&image_desc="+desc+"&new_img_id="+newImgId, function(data){
             if(data == '1'){
                 console.log('success');
             }else{
                 console.log('error: '+data);
             }
         });
     });
     $(document).on("click", ".remove_from_post",function(e){
         $(this).parent().fadeOut(100);
         var imageName = $(this).attr('data-name').split('.')[0];
         $('#'+imageName).remove();
         if($('#'+imageName).hasClass('main-image')){
             $('#'+imageName).removeClass('main-image');
             $(this).parent().find('.set_main_image').text('Set as main');
         }
         $(this).parent().find('.set_main_image').text('Set as main');
         var tmpId = $(this).attr('id').split('-')[2];
         $('#init-image-'+tmpId).show();
     });
     $(document).on("click", ".set_main_image",function(e){
         var imageName = $(this).attr('data-name').split('.')[0];
         $('.hidden_img_info').removeClass('main-image');
         $('#'+imageName).addClass('main-image');
         $('.set_main_image').text('Set as main');
         $(this).text('This is main image');
     });
     $(document).on("click", ".add_description",function(e){
        $(this).parent().find('.desc-wrapper').toggle();
     });
      
      //sending embeded video to post
      
      $('#embeded_video_code').on('keyup', function(){
         $('#videos_tab').prepend(loading);
         var video = $('#embeded_video_code').val();
         $('#videos_tab').find('iframe').remove();
         $('#videos_tab').append(video);
         $('#videos_tab').find('.loading').remove();
         
      });
      $(document).on("click", "#video_submit",function(e){
          e.preventDefault();
           var video = $('#embeded_video_code').val();
          $.post(rootPath + "/public/js/ajax/ajax_config.php", "video_code="+video, function(data){
             if(data == '1'){
                 $('#videos_tab').append('<p>Video uploaded!</p>');
             }else{
                 console.log(data);
             }
         });
      });
      
      // uploading image in post
			$('#post_image_upload').uploadify({
				'formData'     : {
                                        'ajax_page'      : 'post'
				},
				'onUploadSuccess': function(file, data, response) {
                                       var respData = data.split(',');
                                       var userId = respData[0];
                                       var newImgId = respData[1];
                                       $('#images_tab ul').append('<li><img src="'+rootPath + '/public/images/employee/thumb/gallery/'+userId+"_"+file.name+'" alt="'+file.name+'"/>'
                                       +'<a href="#add_to_post" class="add_image_to_post" id="init-image-'+userId+'" data-newimage="'+newImgId+'" data-name="'+userId+"_"+file.name+'">Add to post</a></li>');
                                },
				'swf'      : rootPath + "/public/js/libs/uploadify.swf",
				'uploader' : rootPath + "/public/js/ajax/ajax_config.php",
                                'auto'      	: true,
                                'multi'		: true
			});
     //submiting the post
     
//     $('form#add_post').on('submit', function(){
//            var form = $(this).serialize();
//            alert(form);
//     });
                                
});