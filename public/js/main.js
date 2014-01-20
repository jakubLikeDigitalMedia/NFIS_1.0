$(document).ready(function(){

    // adding new blocks for previous employment
    $('#add-previous-options').click(function(){
       $('div.add-block').append($('div.original').clone().removeClass('original').show());

    });

    $('button.remove-block').on('click', function(){
        var parent = $(this).parent('div.new-block');
        ($('div.new-block').length > 1)? parent.remove(): parent.hide();
    });
    
    //disabling / enabling checkboxes in group permissions
    
     $('.section_page').each(function(){
            if($(this).not(':checked')){
                $(this).parent().nextAll().find('input[type="checkbox"]').attr('disabled','disabled');
            }
    });
    $('.section_page').on('change', function(){
       if($(this).is(':checked')){console.log('asd');
          $(this).parent().nextAll().find('input[type="checkbox"]').removeAttr('disabled');
       }else{
          $(this).parent().nextAll().find('input[type="checkbox"]').attr('disabled','disabled');
       }
    });
    
    //employee table
    
    
    
});