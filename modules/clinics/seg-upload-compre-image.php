<?php
/*
*Created by Maimai
*11-11-2014
*File upload class for comprehensive information
*/

require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'include/care_api_classes/class_compre_discharge.php');

$compre_obj = new Compre_discharge();
$encounter_nr = $_GET["encounter_nr"];

foreach ($_FILES["file"]["error"] as $key => $error){

    if ($error == UPLOAD_ERR_OK){
	    
	    $time=time(); 
		$random_num=rand(00,99);
        $name = $time.$random_num.$_FILES["file"]["name"][$key];
        
        if(move_uploaded_file( $_FILES["file"]["tmp_name"][$key], $root_path.compre_img.$name)){
			$compre_obj->details = array("encounter_nr"=>$encounter_nr,
											"filename"=>$name);
			$compre_obj->insertImages();
			echo "1";
		}else{
			echo "0";
			exit;
    	}
	}
}
?>