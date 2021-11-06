

$('.js-example-basic-single').select2({
});
$('.js-example-basic-single-with-image').select2({
    templateResult: function (idioma) {
  	 var $span = $("<span><img class='main_select2_icon' src='/img/type_sport/"+idioma.id+".png'/> " + idioma.text + "</span>");
  	 return $span;
    }
});

$(function(){
    $('.preloaderMap').remove();	$('#form_updateMap').css({'opacity': 1});});
