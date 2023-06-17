<?php
/*
@author: maimai 
@date created: 05-28-2014
*/	
	include('radio-upload-class.php');
	$image = new RadioUploadImage();

	$file = $_FILES['result_img'];
	$thumbWidth = 171; $thumbHeight = 167;
	
	$return = array("message"=>"","file_name"=>"");

	$file_allowed = $image->check($file);
	if($file_allowed==""){
		
		$file_name = $image->filename(); // get filename
		$image->upload($file["tmp_name"], $file["size"]); // upload image
		$image->load(); //load uploaded image
		$image->resize($thumbWidth, $thumbHeight); // resize to thumbnail
		//$image->save(); // save thumbnail

		$return["file_name"] = $file_name;
	}

	else{
		$return["message"] = $file_allowed;
	}

	echo json_encode($return);
?>