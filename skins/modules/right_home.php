    <div class="clear"></div>
    <div class="height5px"> &nbsp; </div>

<div class="clear"></div>
<div id="tin_noibat">
    <h3 class="hmp-cate-maintitle">
        <span><a class="a-maintitle" href="#" style="color:#FFF;">XEM NHIỀU NHẤT</a></span>
    </h3>
    <div class="tin_anh_hot">
        <ul class="ul_tin_noibat">
            <?php
	$n=count($this->tinnoi);
	//var_dump($this->tinnoi);
	for($i=0; $i<$n; $i++){
		$row=$this->tinnoi[$i];  ?>
        <li>
			<a class="title_home" href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['cat_id'];?>&id=<?php echo $row['id']; ?>">
				<?php echo $row['title']; ?>
             </a>
             <?php
				  preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$row['fulltext']), $mediaop);

					  if(strlen($mediaop[2])<6){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 					  
			   ?>            
				 <img src="<?php echo $img; ?>">			           
        </li>
	<?php }
	
?>
        </ul>
    </div>
</div>   <!---------------------------------------------Tin_anh noi bat------------------------------------------->
<div class="quangcao">    
    	<?php
			$n=count($this->benphaitrangchu);
			for($i=0; $i<$n; $i++){ 
				$row=$this->benphaitrangchu[$i]; ?>
            <div class="qc_up">
				<?php echo str_replace('\"','"',$row['anh']); ?>
            </div>
		<?php	}			
		?>
</div>               <!---------------------------------------------quangcao------------------------------------------->

