<?if($list!=false):?>
<table id="tech-companies-1" class="table table-striped" style="width: 60%; margin-left: 20px;">
  <thead>
    <tr>
      <th id="tech-companies-1-col-0" style="width: 30px;">#</th>
      <th data-priority="1" id="tech-companies-1-col-2">Документ</th>
      <th data-priority="3" id="tech-companies-1-col-1">Посмотреть</th>                                                     
    </tr>
  </thead>
                                                        
  <tbody>
    <?$i=1; foreach($list as $doc):?>
    <tr>
      <th data-org-colspan="1" data-columns="tech-companies-1-col-0" style="width: 30px;"><?=$i;?></th>
      <td data-org-colspan="1" data-priority="1" data-columns="tech-companies-1-col-2"><?=$doc['name'];?></td>
      <td data-org-colspan="1" data-priority="3" data-columns="tech-companies-1-col-1">
        <a href="<?=DOMAIN;?>/files/<?=$doc['document']?>" target="_blank">посмотреть</a>
      </td>                                                  
    </tr>  
    <?$i++; endforeach;?>                                             
  </tbody>
</table>
<?endif;?>