$('body').on('click', '.jsAjaxNav', function() {
  var page = $(this).attr('data-page');
  var btn = $(this).attr('data-form');
        
  $('#jsClickPage').val(page);
  $('#'+btn).click();
  return false;
});