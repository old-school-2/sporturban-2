<!--------------------------------------------------- Затемнение экрана --------------------------------------------------------->
<div id="opaco" class="<?if(empty($xc['message'])):?>hidden<?endif;?>"<?if(!empty($xc['message'])):?> style="height: 2000px;"<?endif;?>>
<img src="/img/loader.gif" <?if(MOBILE==true):?>style="width: 95%;"<?endif;?> />
</div>
<!------------------------------------------------------------------------------------------------------------------------------->

<!---------------------------------------------------- Всплывающее окно --------------------------------------------------------->
<div id="jsPopupWindow" class="popupWindows hidden">
<div class="popupCloseDiv jsClosePopupWindow">&times;</div>

<div class="popupWindowSubDiv" id="jsPopupWindowSubDiv">
<table class="popupWindowsTab">
<tr>
<td>

</td>
</tr>
</table>
</div>

</div>
<!------------------------------------------------------------------------------------------------------------------------------->

<?if(!empty($xc['message'])):?>
<div id="jsPopupWindow" class="popupWindows jsPopupClose" style="width: <?if(MOBILE==true):?>90%;<?else:?>400px;<?endif;?> height: 200px; z-index: 5500;">
<div class="popupCloseDiv jsClosePopupWindow">&times;</div>

<div class="popupWindowSubDiv" id="jsPopupWindowSubDiv">
<table class="popupWindowsTab">
<tr>
<td>
  <div class="regSuccessMessageDiv">
     <?=$xc['message'];?>
  </div>
</td>
</tr>
</table>
</div>

</div>
<?endif;?>