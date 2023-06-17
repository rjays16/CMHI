<?php

$root_path = '../../';
include($root_path.'include/inc_environment_global.php');      
$img_path = $root_path.radresult_img;

$r_img =  $_GET['r_img'];
$name =  $_GET['patient_name'];
$service =  $_GET['service_name'];
$images = explode(',',$r_img);

$file_num = $_GET['file_num'];
$next = $file_num + 1;
$prev = $file_num - 1;

$control_path = $root_path.'images/';

for($i=0; $i<count($images);$i++){
	$images[$i] = $img_path.$images[$i];
}

?>
<div>
	<span>Name: <?php echo $name; ?></span></br>
	<span>Service: <?php echo $service; ?></span>
</div>

<div style = "background-color: black; border:solid-black; width: 100%; height: 100%;">
	
	<div style="width: 10%; display:inline-block; float:left;">
		<?php if ($prev >= 0){ ?> 
			<a href="<?php echo 'seg_radio_imagepreview.php?r_img='.$r_img.'&file_num='.$prev.'&patient_name='.$name.'&service_name='.$service;?>">
				<img src="<?php echo $control_path.'previous.png'; ?>"/>
			</a>
		<?php } else {?>
			<img src="<?php echo $control_path.'previous.png'; ?>" style="opacity: 0.4;"/>
		<?php } ?>
	</div>
	
	<div style="width: 75%; height: 80%; vertical-align:middle; text-align:center; border:solid black; float:left; ">
		 <img style="height: 100%;" src="<?php echo $images[$file_num];?>" />
	</div>

	<div style="float:left; width: 10%;">
		<?php if ($next < count($images)){ ?> 
			<a href="<?php echo 'seg_radio_imagepreview.php?r_img='.$r_img.'&file_num='.$next.'&patient_name='.$name.'&service_name='.$service;?>">
				<img src="<?php echo $control_path.'next.png'; ?>"/>
			</a>
		<?php } else{ ?>
			<img src="<?php echo $control_path.'next.png'; ?>" style = " opacity: 0.4;"/>
		<?php } ?>
	</div>

</div>