//$(function(){
//   //selecting a group and reloading the table data
//   $('.filter_by_value').on('change', function(){
//      var category = $(this).attr('name');
//      var filter_by = $(this).val();
//      filter_by = $('select[name="'+category+'"]').find('option[value="'+filter_by+'"]').text();
//          dynatable.settings.dataset.ajax = true;
//          dynatable.settings.dataset.ajaxCache = true;
//          dynatable.settings.dataset.ajaxUrl = '../../scripts/employee/employee_add_to_group.php';
//          dynatable.settings.dataset.ajaxMethod = "GET";
//          dynatable.processingIndicator.show();
//          
//          dynatable.queries.add("position",filter_by);console.log(dynatable.queries);
//       dynatable.process();dynatable.processingIndicator.hide(); 
////      $.post('../../scripts/employee/employee_add_to_group.php', "category="+category+"&"+"filter_by="+filter_by, function(data){
////         
////          dynatable.settings.dataset.records = $.parseJSON(data);
////          //dynatable.settings.dataset.originalRecords = $.parseJSON(data);
////          dynatable.dom.update();
////          dynatable.process();
////          dynatable.processingIndicator.hide(); console.log(dynatable.settings.dataset);
////      });
//   });
//});