!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)}(function(a){var b,c=navigator.userAgent,d=/iphone/i.test(c),e=/chrome/i.test(c),f=/android/i.test(c);a.mask={definitions:{9:"[0-9]",a:"[A-Za-z]","*":"[A-Za-z0-9]"},autoclear:!0,dataName:"rawMaskFn",placeholder:"_"},a.fn.extend({caret:function(a,b){var c;if(0!==this.length&&!this.is(":hidden"))return"number"==typeof a?(b="number"==typeof b?b:a,this.each(function(){this.setSelectionRange?this.setSelectionRange(a,b):this.createTextRange&&(c=this.createTextRange(),c.collapse(!0),c.moveEnd("character",b),c.moveStart("character",a),c.select())})):(this[0].setSelectionRange?(a=this[0].selectionStart,b=this[0].selectionEnd):document.selection&&document.selection.createRange&&(c=document.selection.createRange(),a=0-c.duplicate().moveStart("character",-1e5),b=a+c.text.length),{begin:a,end:b})},unmask:function(){return this.trigger("unmask")},mask:function(c,g){var h,i,j,k,l,m,n,o;if(!c&&this.length>0){h=a(this[0]);var p=h.data(a.mask.dataName);return p?p():void 0}return g=a.extend({autoclear:a.mask.autoclear,placeholder:a.mask.placeholder,completed:null},g),i=a.mask.definitions,j=[],k=n=c.length,l=null,a.each(c.split(""),function(a,b){"?"==b?(n--,k=a):i[b]?(j.push(new RegExp(i[b])),null===l&&(l=j.length-1),k>a&&(m=j.length-1)):j.push(null)}),this.trigger("unmask").each(function(){function h(){if(g.completed){for(var a=l;m>=a;a++)if(j[a]&&C[a]===p(a))return;g.completed.call(B)}}function p(a){return g.placeholder.charAt(a<g.placeholder.length?a:0)}function q(a){for(;++a<n&&!j[a];);return a}function r(a){for(;--a>=0&&!j[a];);return a}function s(a,b){var c,d;if(!(0>a)){for(c=a,d=q(b);n>c;c++)if(j[c]){if(!(n>d&&j[c].test(C[d])))break;C[c]=C[d],C[d]=p(d),d=q(d)}z(),B.caret(Math.max(l,a))}}function t(a){var b,c,d,e;for(b=a,c=p(a);n>b;b++)if(j[b]){if(d=q(b),e=C[b],C[b]=c,!(n>d&&j[d].test(e)))break;c=e}}function u(){var a=B.val(),b=B.caret();if(o&&o.length&&o.length>a.length){for(A(!0);b.begin>0&&!j[b.begin-1];)b.begin--;if(0===b.begin)for(;b.begin<l&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}else{for(A(!0);b.begin<n&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}h()}function v(){A(),B.val()!=E&&B.change()}function w(a){if(!B.prop("readonly")){var b,c,e,f=a.which||a.keyCode;o=B.val(),8===f||46===f||d&&127===f?(b=B.caret(),c=b.begin,e=b.end,e-c===0&&(c=46!==f?r(c):e=q(c-1),e=46===f?q(e):e),y(c,e),s(c,e-1),a.preventDefault()):13===f?v.call(this,a):27===f&&(B.val(E),B.caret(0,A()),a.preventDefault())}}function x(b){if(!B.prop("readonly")){var c,d,e,g=b.which||b.keyCode,i=B.caret();if(!(b.ctrlKey||b.altKey||b.metaKey||32>g)&&g&&13!==g){if(i.end-i.begin!==0&&(y(i.begin,i.end),s(i.begin,i.end-1)),c=q(i.begin-1),n>c&&(d=String.fromCharCode(g),j[c].test(d))){if(t(c),C[c]=d,z(),e=q(c),f){var k=function(){a.proxy(a.fn.caret,B,e)()};setTimeout(k,0)}else B.caret(e);i.begin<=m&&h()}b.preventDefault()}}}function y(a,b){var c;for(c=a;b>c&&n>c;c++)j[c]&&(C[c]=p(c))}function z(){B.val(C.join(""))}function A(a){var b,c,d,e=B.val(),f=-1;for(b=0,d=0;n>b;b++)if(j[b]){for(C[b]=p(b);d++<e.length;)if(c=e.charAt(d-1),j[b].test(c)){C[b]=c,f=b;break}if(d>e.length){y(b+1,n);break}}else C[b]===e.charAt(d)&&d++,k>b&&(f=b);return a?z():k>f+1?g.autoclear||C.join("")===D?(B.val()&&B.val(""),y(0,n)):z():(z(),B.val(B.val().substring(0,f+1))),k?b:l}var B=a(this),C=a.map(c.split(""),function(a,b){return"?"!=a?i[a]?p(b):a:void 0}),D=C.join(""),E=B.val();B.data(a.mask.dataName,function(){return a.map(C,function(a,b){return j[b]&&a!=p(b)?a:null}).join("")}),B.one("unmask",function(){B.off(".mask").removeData(a.mask.dataName)}).on("focus.mask",function(){if(!B.prop("readonly")){clearTimeout(b);var a;E=B.val(),a=A(),b=setTimeout(function(){B.get(0)===document.activeElement&&(z(),a==c.replace("?","").length?B.caret(0,a):B.caret(a))},10)}}).on("blur.mask",v).on("keydown.mask",w).on("keypress.mask",x).on("input.mask paste.mask",function(){B.prop("readonly")||setTimeout(function(){var a=A(!0);B.caret(a),h()},0)}),e&&f&&B.off("input.mask").on("input.mask",u),A()})}})});

$('body').on('focus', '.phoneMask', function() {
    $(".phoneMask").mask("+7 (999) 999-9999");
});

function isJSON(data) {
   var ret = true;
   try {
      JSON.parse(data);
   }catch(e) {
      ret = false;
   }
   return ret;
}

function redBorder(form_id, name, act) {
        if (act == true) {
           $('#' + form_id).find('[name="' + name + '"]').css('box-shadow','0 0 7px #ff0000');
        } else {
           $('#' + form_id).find('[name="' + name + '"]').css('box-shadow','none');
        }
}

function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + (Math.round(n * k) / k).toFixed(prec);
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
}

$('body').on('keyup', '.jsSelectList', function() {
    
    var t = $(this);
    var obj = t.attr('id');
    var search = $('#' + obj).val();
    var mod = $('#' + obj + '_mod').val();
    var com = $('#' + obj + '_com').val();
    var arr = $('#' + obj + '_arr').val();
    var obj_id = $('#' + obj + '_id').val();
    
    if (search == '') {
        $('#' + obj + '_id').val('');
    }
    
       $.post("/", {search: search, arr: arr, obj_id: obj_id, action: "ajax", field: obj, module: mod, component: com},
        	function (html) {
        	   
              if (obj_id == 'alert') {
                alert(html)
              }
        	   
     	      if (html!='') {
        	   $('#'+obj+'2').removeClass('hidden').html(html);
        	  }
              
              else {
                $('#'+obj+'2').addClass('hidden');
              }
		});
  });
  
$('body').on('click', '.jsSelect', function() {
        
        var t = $(this);
        var field = t.attr('id');
        var value = t.attr('data-id');
        var text = t.text();
        var btn = t.attr('data-click');
        
        var input = t.attr('data-input');
        var inputVal = t.attr('data-input-val');
        
        $('#'+field+'_id').val(value);
        $('#'+field).val(text);
        $('#'+field+'2').addClass('hidden');
        
        $('#'+input).val(inputVal);
        
        $('#'+btn).click();
});

$('body').on('focus', '.jsClearInput', function() {
        $('.jsClear').val('');
});

$('body').on('keyup', '.jsFormatNum', function() {
        var t = $(this);
        var num = t.val();
        if (num != '') {
           var formatNum = number_format(num, 0, '', ' ');
           t.val(formatNum);
        }
});

$('body').on('keyup', '.jsFormatLetter', function() {
      var that = this;
      var value = $(this).val();
      
      setTimeout(function() {
        var res = /[^а-яА-ЯёЁ]/g.exec(that.value);
        that.value = that.value.replace(res, '');
      }, 0);
      
      if (value!='') {
        $(this).val(value[0]);
      }
});

$('body').on('click', '.jsClosePopupWindow', function() {
    
        var count = $('.jsPopupClose').length;
        $(this).parent().remove();
        
        if (count < 2) {
            $('#opaco').fadeOut(300).addClass('hidden');
        }
        
        return false;
});

$('body').on('click', '.send_form', function() {
    
        var but_name = $(this).attr('name');
        
        
        if (but_name == 'del') {
            
          var delText = $(this).attr('data-text');
            
          if (!confirm(delText)) {
             return false;
          }
    
        }
        
        var button_id = $(this).attr('id');
        var form_id = 'form_' + button_id;
        var str = $('#' + form_id).serializeArray();
        var e = false;
        var name;
        var i;
        var images;
        
        console.log('send form');
        
        var formData = new FormData();
        var arr = {
          'is_ajax':false,
          'top':false,
          'ok':false,
          'opaco':0,
          'module':'',
          'component':'',
          'closeWindow':false,
          'closeThisWindow':false,
          'tinymce':false,
          'ckeditorAjax':false,
          'ajaxLoad':false,
          'ajaxLoadClear':false,
          'scroll':false,
          'removeElement':false,
          'callbackFunc':'',
          'loadImg':false,
          'loadImgMultiple':false,
          'clearForm':false,
          'alert':false
        };

        for (i = str.length - 1; i >= 0; --i) {
                name = str[i]['name'];
                if (name.substr(name.length - 1) == '@') {
                        str[i]['name'] = name.substr(0, name.length - 1);
                        
                        if ( $('input[name="'+but_name+'"]').hasClass('withoutObligations') != true && $('button[name="'+but_name+'"]').hasClass('withoutObligations') != true ) {
                            
                            if (str[i]['value'] == '') {
                                e = true;
                                redBorder(form_id, name, e);
                            } else {
                                redBorder(form_id, name, false);
                            }
                        }  
                }
                
                if (str[i]['name'] in arr) {
                    arr[ str[i]['name'] ] = str[i]['value'];
                } else {
                    formData.append(str[i]['name'], str[i]['value']);
                }
        }
        
        if (e == true) {
                if (arr['top'] == 1) {
                        $('html, body').animate({scrollTop: $('#'+form_id).offset().top}, 500);
                }
                
                alert('Заполните поля подсвеченные красным цветом');
                return false;
        }
        
        if (arr['tinymce'] == 1) {
            formData.append('text', tinyMCE.get(button_id + '2').getContent() );
        }
        
        if (arr['ckeditorAjax'] != false) {
            formData.append('text', CKEDITOR.instances[ arr['ckeditorAjax'] ].getData() );
        }
        
        if (arr['loadImg'] == 1) {
            $.each($('#' + button_id + '_file')[0].files, function(i, file) {
                formData.append('file-' + i, file);
            });
        }
        
        if (arr['loadImgMultiple'] == 1) {
            $.each($('#' + button_id + '_file')[0].files, function(i, file) {
                formData.append('file-' + i, file);
            });
            
            $.each($('#' + button_id + '_file2')[0].files, function(i, file) {
                formData.append('file2-' + i, file);
            });
        }
       
        if (arr['is_ajax'] != 1) {
            
                $('#' + button_id).attr("disabled", true);
                var h = $(document).outerHeight(true);
                
                $('#opaco').height(h).fadeIn(200).removeClass('hidden');
                
                formData.append('module', arr['module']);
                formData.append('component', arr['component']);
                formData.append('action', 'ajax');
                formData.append('but', but_name);
                formData.append('form_id', form_id);
                
                $.ajax({
                        url: "/",
                        type: "POST",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(html) {
                      
                       $('#' + button_id).attr("disabled", false);
                       
                       if (arr['alert'] != false) {
                         alert(html);
                       }
                       
                       var popupWindow = 'jsPopupWindow';
                       
                       if (isJSON(html) == true) {
                        
                          var myArr = $.parseJSON(html);
                        
                          if (myArr[0] == 'popup') {
                            
                            var cl = $('#'+popupWindow).clone();
                            
                            cl.find('#'+popupWindow+'SubDiv').css({'height':myArr[3]});
                            cl.find('td').html(myArr[1]);
                            
                            cl.css({'width':myArr[2],'height':myArr[3],'z-index':myArr[4]}).appendTo('body').fadeIn(200).removeClass('hidden').addClass('jsPopupClose');
                            return false;
                          }
                          
                          if (myArr[0] == 'redirect') {
                             window.location.href = myArr[1];
                          }
                          
                       }
                       
                        if (arr['ajaxLoadClear'] != false) {
                           if ( $('#'+arr['ajaxLoadClear']).html() != '' ) {
                              $('#'+arr['ajaxLoadClear']).html('');
                              $('#opaco').addClass('hidden');
                              return false;
                           }
                       }
                        
                        if (arr['ajaxLoad'] != false) {
                           $('#'+arr['ajaxLoad']).html(html);
                           html = 'ok';
                        }
                        
                        if (arr['callbackFunc'] != '') {
                           var q1 = eval( arr['callbackFunc'] )(html);
                           html = 'ok';
                        }
                        
                        if (arr['clearForm'] != false) {
                            $('#'+form_id)[0].reset();
                        }
                        
                        if (arr['removeElement']!=false) {
                            $('#'+arr['removeElement']).remove();
                        }
  
                        if (arr['closeWindow'] != false) {
                           $('.jsPopupClose').remove();
                        }
                        
                        if (arr['closeThisWindow'] != false) {
                           $('#'+arr['closeThisWindow']).parent().parent().parent().parent().parent().parent().remove();
                        }
                        
                        if (html == 'ok') {
                            
                            if (arr['ok'] != false) {
                             
                              var windowWidth = 400;
                            
                              var cl = $('#'+popupWindow).clone();
                              
                              cl.find('#'+popupWindow+'SubDiv').css({'height':200});
                              cl.find('td').html('<div class="regSuccessMessageDiv">'+arr['ok']+'</div>');
                              cl.css({'width':windowWidth,'height':200,'z-index':100000}).appendTo('body').fadeIn(200).removeClass('hidden').addClass('jsPopupClose');
                            }   
                            
                        }
                        
                        if (arr['scroll'] != false) {
                            
                            var heightTop = $('#'+arr['scroll']).offset().top;
                            
                             $('html, body').animate({
                                        scrollTop: heightTop
                              }, 500);
                        }
                        
                        if (arr['opaco'] == 1) {
                            $('#opaco').fadeOut(550).addClass('hidden');
                        }
                    }
                });
                return false;
        }
});