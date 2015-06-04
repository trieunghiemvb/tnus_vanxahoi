 <div id="tin_noibat">
          <h3 class="hmp-cate-maintitle">
              <span><a class="a-maintitle" href="#" style="color:#FFF;">Tin Nổi Bật</a></span>
          </h3>
                <div class="tin_anh_hot">
                    <ul class="ul_tin_noibat">
                       	<?php
	$n=count($this->xemnhieu);
	for($i=0; $i<$n; $i++){ 
		$row=$this->xemnhieu[$i];
?>
		<li>
                        	<a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['cat_id']; ?>&id=<?php echo $row['id']; ?>">	
                            	<?php
                                	preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$row['fulltext']), $mediaop);
								if(strlen($mediaop[2])<6){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 					  
			   ?>            
				 <img src="<?php echo $img; ?>">
                            </a>
                            <div class="txtNB">
                            	<a class="tieude_trangcon" href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['cat_id']; ?>&id=<?php echo $row['id']; ?>">
                                	<h4><?php echo mb_substr($row['title'],0,40,'UTF-8')?></h4>
                                </a>
                            </div>
                        </li>
<?php	}

?>                      
                    </ul>
                </div>
<div class="quangcao">    
    	<?php
			$n=count($this->benphaitrangchu);
			for($i=0; $i<$n; $i++){ 
				$row=$this->benphaitrangchu[$i]; ?>
            <div class="qc_up" style="margin-bottom:5px;">
				<?php echo str_replace('\"','"',$row['anh']); ?>
            </div>
		<?php	}			
		?>
</div>               <!---------------------------------------------quangcao------------------------------------------->  