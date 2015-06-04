<link href="css/CSS.css" rel="stylesheet" type="text/css" />
<link href="css/cssthitruong.css" rel="stylesheet" type="text/css" />

<div id="picSH">
      <div class="infoSH">
          <a style="font-size: 16px;
    font-weight: bold;" href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $this->picsh['cat_id']; ?>&id=<?php echo $this->picsh['id']; ?>">
				<?php echo $this->picsh['title']; ?>
          </a>
      </div>
      <a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $this->picsh['cat_id']; ?>&id=<?php echo $this->picsh['id']; ?>">
      	<?php
									preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$this->picsh['fulltext']), $mediaop);																				
										if(strlen($mediaop[2])<7){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 
										
								 ?>
      		<img class="pic" src="<?php echo $img;?>" />
      </a>
</div> <!--end_picSh-->





<!----------------------------HIen thi o trang chu vung to nhat --------------------------->
<!------------------------------------------------------->


                <div id="categori_show">
                	<div class="h3">
                    	<h3 class="hmp-cate-maintitle">
                    		<span><a class="a-maintitle" href="#" style="color:#FFF;">Tin Nổi Bật</a></span>
                    	</h3>
                    </div>          <!---------------------HIEN THI TIEU DE-------------------->
                    <ul class="ul_hmp-cate-hot">
                    	<?php
						$n=count($this->tinmoi_r);
						//echo $n;
						for($i=0; $i<$n; $i++){
							$row=$this->tinmoi_r[$i];  ?>
							<li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['cat_id'];?>&id=<?php echo $row['id']; ?>">
								<?php echo $row['title'] ?>
							</a></li> 
						<?php	}
						?>
                                                             
                    </ul>
                </div>  <!----------------------------------categori_show---------------------------->
                			<div class="clear"></div>
                			<div class="height3px"> &nbsp; </div>
                <div id="categoriNgang">
                			<div class="height5px"> &nbsp; </div>
                	<ul class="list_ngang">
                    	<?php
	$n=count($this->list_content);
	//echo $n;
	for($i=0; $i<$n; $i++){
		$row=$this->list_content[$i];  ?>
        	<li>
     			<a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['cat_id']; ?>&id=<?php echo $row['id'] ?>">
                <?php
									preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$row['fulltext']), $mediaop);
		
										if(strlen($mediaop[2])<6){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 
										
								 ?>
                	<img class="anh_ngang" src="<?php echo $img; ?>" />
                </a>
     			<h2><a style="font-size:12px;word-wrap:break-word;" href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&id=<?php echo $row['id'] ?>">	
                		
						<?php echo $row['title']; ?>
                </a></h2>
			</li>
<?php	}
?>
                        
                    </ul>
                </div> <!----------------------------------categoriNgang---------------------------->
              
<!------------------------------------------------------->
<!-------------------------------------------------------><!------------------------------------------------------->
<!------------------------------------------------------->

<style>
.intro{
		
	width:310px;
}
</style>
<?php
	$n=count($this->menu_sp);
	$k=0;
	//echo $n;
	for($i=0;$i<$n; $i++){
		$row=$this->menu_sp[$i];?>
        
        	<div class="hmp-cate-wrap">
               		<div class="h3">
                    	<h3 class="hmp-cate-maintitle">
                    		<span><a class="a-maintitle" href="<?php echo INDEX?>?app=list_danhmuc&act=hienthi_listdanhmuc&ma_dm=<?php echo $row['id'];?>" style="color:#FFF;"><?php echo $row['title'];?></a></span>
                    	</h3>
                    </div>   <!------------------------------  HIEN THI TIEU DE ------------------------------------------>  
                    <div class="hmp-cate-banner">
            <?php
            	if($row['id']==49){
					$to=count($this->kia);
					$uk=0;
					for($u=0; $u<$to; $u++){
						$row1=$this->kia[$u]; 
							if($uk==0){ 
							
							
								if($row1['cat_id']==$row['id']){?>
							<div class="hmp-cate-pic">
                            	<a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">					<?php
									preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$row1['fulltext']), $mediaop);
		
										if(strlen($mediaop[2])<6){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 
										
								 ?>
                                	<img src="<?php echo $img; ?>" /></a>
                            </div>
                            <h4 class="hmp-top-articletitle">
								<a  href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>"><?php echo $row1['title']; ?></a>
							</h4>
                            <div class="intro"><?php echo mb_substr($row1['introtext'],0,100,'UTF-8');?></div>
                            <?php $uk=($uk+1);$u++;}else{$u++;}} ?>
                             
                              	<?php
								$row1=$this->kia[$u];
								if($uk==1){ 
								if($row1['cat_id']==$row['id']){?> 
                                 <ul class="ul_hmp-cate-banner">
								 
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a>
                                  </li>  
                                  <?php $uk=($uk+1);$u++;}else{$u++;}} ?>                     
                                  <?php
								  $row1=$this->kia[$u];
								if($uk==2){ 
								if($row1['cat_id']==$row['id']){?>                                           
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a></a>
                                  </li>
                                  </ul>
                                  
                                    <?php $uk=($uk+1);$u++;}else{$u++;}} ?> 
                           	    
                               
			<?php		
				}}
				
			?>   	
            
             <?php
            	if($row['id']==54){
					
					$to=count($this->huyndai);
					$uk=0;
					for($u=0; $u<$to; $u++){
						$row1=$this->huyndai[$u]; 
							if($uk==0){ 
							
							
								if($row1['cat_id']==54){?>
							<div class="hmp-cate-pic">
                            	<a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">					<?php
									preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$row1['fulltext']), $mediaop);
		
										if(strlen($mediaop[2])<7){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 
										
								 ?>
                                	<img src="<?php echo $img; ?>" /></a>
                            </div>
                            <h4 class="hmp-top-articletitle">
								<a  href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>"><?php echo $row1['title']; ?></a>
							</h4>
                            <div class="intro"><?php echo mb_substr($row1['introtext'],0,100,'UTF-8');?></div>
                            <?php $uk=($uk+1);$u++;}else{$u++;}} ?>
                             
                              	<?php
								$row1=$this->huyndai[$u];
								if($uk==1){ 
								if($row1['cat_id']==54){?> 
                                 <ul class="ul_hmp-cate-banner">
								 
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a>
                                  </li>  
                                  <?php $uk=($uk+1);$u++;}else{$u++;}} ?>                     
                                  <?php
								  $row1=$this->huyndai[$u];
								if($uk==2){ 
								if($row1['cat_id']==54){?>                                           
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a></a>
                                  </li>
                                  </ul>
                                  
                                    <?php $uk=($uk+1);$u++;}else{$u++;}} ?> 
                           	    
                               
			<?php		
				}}
				
			?>  
            
            
            
             <?php
            	if($row['id']==50){
					
					
					$to=count($this->nissan);
					$uk=0;
					for($u=0; $u<$to; $u++){
						$row1=$this->nissan[$u]; 
							if($uk==0){ 
							
							
								if($row1['cat_id']==50){?>
							<div class="hmp-cate-pic">
                            	<a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">					<?php
									preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$row1['fulltext']), $mediaop);
		
										if(strlen($mediaop[2])<7){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 
										
								 ?>
                                	<img src="<?php echo $img; ?>" /></a>
                            </div>
                            <h4 class="hmp-top-articletitle">
								<a  href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>"><?php echo $row1['title']; ?></a>
							</h4>
                            <div class="intro"><?php echo mb_substr($row1['introtext'],0,100,'UTF-8');?></div>
                            <?php $uk=($uk+1);$u++;}else{$u++;}} ?>
                             
                              	<?php
								$row1=$this->nissan[$u];
								if($uk==1){ 
								if($row1['cat_id']==50){?> 
                                 <ul class="ul_hmp-cate-banner">
								 
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a>
                                  </li>  
                                  <?php $uk=($uk+1);$u++;}else{$u++;}} ?>                     
                                  <?php
								  $row1=$this->nissan[$u];
								if($uk==2){ 
								if($row1['cat_id']==50){?>                                           
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a></a>
                                  </li>
                                  </ul>
                                  
                                    <?php $uk=($uk+1);$u++;}else{$u++;}} ?> 
                           	    
                               
			<?php		
				}}
				
			?> 
            
            
            
            
             
              <?php
            	if($row['id']==47){
					
					
					$to=count($this->thethao);
					$uk=0;
					for($u=0; $u<$to; $u++){
						$row1=$this->thethao[$u]; 
							if($uk==0){ 
							
							
								if($row1['cat_id']==47){?>
							<div class="hmp-cate-pic">
                            	<a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">					<?php
									preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$row1['fulltext']), $mediaop);
		
										if(strlen($mediaop[2])<7){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 
										
								 ?>
                                	<img src="<?php echo $img; ?>" /></a>
                            </div>
                            <h4 class="hmp-top-articletitle">
								<a  href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>"><?php echo $row1['title']; ?></a>
							</h4>
                            <div class="intro"><?php echo mb_substr($row1['introtext'],0,100,'UTF-8');?></div>
                            <?php $uk=($uk+1);$u++;}else{$u++;}} ?>
                             
                              	<?php
								$row1=$this->thethao[$u];
								if($uk==1){ 
								if($row1['cat_id']==47){?> 
                                 <ul class="ul_hmp-cate-banner">
								 
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a>
                                  </li>  
                                  <?php $uk=($uk+1);$u++;}else{$u++;}} ?>                     
                                  <?php
								  $row1=$this->thethao[$u];
								if($uk==2){ 
								if($row1['cat_id']==47){?>                                           
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a></a>
                                  </li>
                                  </ul>
                                  
                                    <?php $uk=($uk+1);$u++;}else{$u++;}} ?> 
                           	    
                               
			<?php		
				}}
				
			?> 
            
            
            
             <?php
            	if($row['id']==53){
					
					
					$to=count($this->BMW);
					$uk=0;
					for($u=0; $u<$to; $u++){
						$row1=$this->BMW[$u]; 
							if($uk==0){ 
							
							
								if($row1['cat_id']==53){?>
							<div class="hmp-cate-pic">
                            	<a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">					<?php
									preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$row1['fulltext']), $mediaop);
		
										if(strlen($mediaop[2])<7){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 
										
								 ?>
                                	<img src="<?php echo $img; ?>" /></a>
                            </div>
                            <h4 class="hmp-top-articletitle">
								<a  href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>"><?php echo $row1['title']; ?></a>
							</h4>
                            <div class="intro"><?php echo mb_substr($row1['introtext'],0,100,'UTF-8');?></div>
                            <?php $uk=($uk+1);$u++;}else{$u++;}} ?>
                             
                              	<?php
								$row1=$this->BMW[$u];
								if($uk==1){ 
								if($row1['cat_id']==53){?> 
                                 <ul class="ul_hmp-cate-banner">
								 
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a>
                                  </li>  
                                  <?php $uk=($uk+1);$u++;}else{$u++;}} ?>                     
                                  <?php
								  $row1=$this->BMW[$u];
								if($uk==2){ 
								if($row1['cat_id']==53){?>                                           
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a></a>
                                  </li>
                                  </ul>
                                  
                                    <?php $uk=($uk+1);$u++;}else{$u++;}} ?> 
                           	    
                               
			<?php		
				}}
				
			?> 
            
            
            
            
            
            
            
             <?php
            	if($row['id']==48){
					
					
					$to=count($this->toyota);
					$uk=0;
					for($u=0; $u<$to; $u++){
						$row1=$this->toyota[$u]; 
							if($uk==0){ 
							
							
								if($row1['cat_id']==48){?>
							<div class="hmp-cate-pic">
                            	<a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">					<?php
									preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$row1['fulltext']), $mediaop);
		
										if(strlen($mediaop[2])<7){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 
										
								 ?>
                                	<img src="<?php echo $img; ?>" /></a>
                            </div>
                            <h4 class="hmp-top-articletitle">
								<a  href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>"><?php echo $row1['title']; ?></a>
							</h4>
                            <div class="intro"><?php echo mb_substr($row1['introtext'],0,100,'UTF-8');?></div>
                            <?php $uk=($uk+1);$u++;}else{$u++;}} ?>
                             
                              	<?php
								$row1=$this->toyota[$u];
								if($uk==1){ 
								if($row1['cat_id']==48){?> 
                                 <ul class="ul_hmp-cate-banner">
								 
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a>
                                  </li>  
                                  <?php $uk=($uk+1);$u++;}else{$u++;}} ?>                     
                                  <?php
								  $row1=$this->toyota[$u];
								if($uk==2){ 
								if($row1['cat_id']==48){?>                                           
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a></a>
                                  </li>
                                  </ul>
                                  
                                    <?php $uk=($uk+1);$u++;}else{$u++;}} ?> 
                           	    
                               
			<?php		
				}}
				
			?> 
            
            
            
            
            
            
            
            
					<?php
					
					$to=count($this->list_danhmuc);
					$uk=0;
					for($u=-2; $u<$to; $u++){
						$row1=$this->list_danhmuc[$u]; 
							if($uk==0){ 
							
							
								if($row1['cat_id']==$row['id']){?>
                                
							<div class="hmp-cate-pic">
                            	<a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">					<?php
									preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",str_replace('\"','"',$row1['fulltext']), $mediaop);
		
										if(strlen($mediaop[2])<7){$img="media/no_image.jpg";}else{$img=$mediaop[2];} 
										
								 ?>
                                	<img src="<?php echo $img; ?>" /></a>
                            </div>
                            <h4 class="hmp-top-articletitle">
								<a  href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>"><?php echo $row1['title']; ?></a>
							</h4>
                            <div class="intro"><?php echo mb_substr($row1['introtext'],0,100,'UTF-8');?></div>
                            <?php $uk=($uk+1);$u++;}else{$u++;}} ?>
                             
                              	<?php
								$row1=$this->list_danhmuc[$u];
								if($uk==1){ 
								if($row1['cat_id']==$row['id']){?> 
                                 <ul class="ul_hmp-cate-banner">
								 
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a>
                                  </li>  
                                  <?php $uk=($uk+1);$u++;}else{$u++;}} ?>                     
                                  <?php
								  $row1=$this->list_danhmuc[$u];
								if($uk==2){ 
								if($row1['cat_id']==$row['id']){?>                                           
                                  <li><a href="<?php echo INDEX?>?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $row['id'];?>&id=<?php echo $row1['id']; ?>">
                                      <?php echo $row1['title'] ?> </a></a>
                                  </li>
                                  </ul>
                                  
                                    <?php $uk=($uk+1);$u++;}else{$u++;}} ?> 
                           	    
                               
			<?php		
				}
				
			?>   	 
      </div>  <!------------------------------  hmp-cate-banner ------------------------------------------>            			
</div>   <!------------------------------ hmp-cate-wrap ------------------------------------------>     
<?php	}
?>
 