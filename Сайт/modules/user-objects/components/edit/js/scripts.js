function jsEditObjectFunc(html) {
    
    if (isJSON(html) != true) {
        return false;
    }
    
    var data = $.parseJSON(html);
    
    $('#jsUpdateTitle').html(data['object']);
    $('#jsUpdateSportZoneList').html(data['sportzone']);
}

$('body').on('click', '.jsDownloadFile', function() {
    
        var h = $(document).outerHeight(true);
        $('#opaco').height(h).fadeIn(200).removeClass('hidden');
        
        var popupWindow = 'jsPopupWindow';
        
        var cl = $('#'+popupWindow).clone();
        cl.find('#'+popupWindow+'SubDiv').css({'height':'200'});
        cl.find('td').html('<div class="regSuccessMessageDiv">Идёт генерация файла. Скоро начнётся загрузка...</div>');
        cl.css({'width':'400','height':'200','z-index':'6000'}).appendTo('body').fadeIn(200).removeClass('hidden').addClass('jsPopupClose');
});