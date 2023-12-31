<?php
	/* Load the barcode img if it exists */
    if(file_exists($root_path.'cache/barcodes/pn_'.$fen.'.png'))
	{
	   $bc = ImageCreateFrompng($root_path.'cache/barcodes/pn_'.$fen.'.png');
	}elseif(file_exists($root_path.'cache/barcodes/en_'.$fen.'.png')){
	   $bc = ImageCreateFrompng($root_path.'cache/barcodes/en_'.$fen.'.png');
	}	 
	
	 /* Dimensions of the patient's label */
	 $label_w=282; 
	 $label_h=178;
	 #echo "here";
    // -- create label 
    $label=ImageCreate($label_w,$label_h);
    $ewhite = ImageColorAllocate ($label, 255,255,255); //white bkgrnd
    $elightgreen= ImageColorAllocate ($label, 205, 225, 236);
    $eblue=ImageColorAllocate($label, 0, 127, 255);
    $eblack = ImageColorAllocate ($label, 0, 0, 0);
	$egray= ImageColorAllocate($label,127,127,127);
	//ImageFillToBorder($label,2,2,$egray,$ewhite);
	ImageRectangle($label,0,0,281,177,$egray);
	
	# Write the data on the label
	# Location info, admission class, blood group
	$locstr=strtoupper($location['dept_id']).' '.strtoupper($location['ward_id']).' '.$location['roomprefix'];
	if($location['room_nr'])  $locstr.='-'.$location['room_nr'];
	if($location['bed_nr']) $locstr.=' '.strtoupper(chr($location['bed_nr']+96));
	$locstr.=' '.$admit_type.' '.$LDInsShortID[$result['insurance_class_nr']];
	
	# Check if ttf is ok
	include_once($root_path.'include/inc_ttf_check.php');
	
	//$verdana='VERDANA.TTF';
	
	//if(function_exists(ImageTTFText)&&file_exists($font_path.$arial)&&file_exists($font_path.$verdana)){
	
	if($ttf_ok){
		
		$tmargin=2;
		$lmargin=6;
		
		#  Full encounter nr
    	ImageTTFText($label,12,0,$lmargin,$tmargin+14,$eblack,$arial,$fen);
		# Encounter admission date
    	ImageTTFText($label,11,0,$lmargin,$tmargin+30,$eblack,$arial,$result['pdate']);
		# Family name, first name
    	ImageTTFText($label,16,0,$lmargin,$tmargin+56,$eblack,$arial,$result['name_last'].', '.$result['name_first']);
		# Date of birth
    	ImageTTFText($label,11,0,$lmargin,$tmargin+74,$eblack,$arial,$result['date_birth']);
		# Address street nr, street name
#    	ImageTTFText($label,11,0,$lmargin,$tmargin+93,$eblack,$arial,ucfirst($result['addr_str']).' '.$result['addr_str_nr']);
    	ImageTTFText($label,11,0,$lmargin,$tmargin+93,$eblack,$arial,ucfirst($result['street_name']).', '.$result['brgy_name']);   # burn added : July 24, 2007
		# Address, zip, city/town name
#    	ImageTTFText($label,11,0,$lmargin,$tmargin+108,$eblack,$arial,ucfirst($result['addr_zip']).' '.$result['citytown_name']);
    	ImageTTFText($label,11,0,$lmargin,$tmargin+108,$eblack,$arial,$result['mun_name'].' '.$result['zipcode'].' '.$result['prov_name']);   # burn added : July 24, 2007
		# Sex
    	ImageTTFText($label,14,0,$lmargin,$tmargin+130,$eblack,$arial,strtoupper($result['sex']));
		# Family name, repeat print
    	ImageTTFText($label,14,0,$lmargin+30,$tmargin+130,$eblack,$arial,$result['name_last']);
		# Insurance co name
    	ImageTTFText($label,14,0,$lmargin,$tmargin+150,$eblack,$arial,$ins_obj->getFirmName($result['insurance_firm_id']));
		# Location
		#edited by VAN 07-10-08
    	if ($en)
			ImageTTFText($label,9,0,$lmargin,$tmargin+170,$eblack,$arial,$locstr);
		else
			ImageTTFText($label,9,0,$lmargin,$tmargin+170,$eblack,$arial,$locstr2.' '.$LDInsShortID[$result['insurance_class_nr']]);	
		#Blood group
		if(stristr('AB',$result['blood_group'])){
    		ImageTTFText($label,24,0,$lmargin+240,$tmargin+127,$eblack,$arial,$result['blood_group']);
		}else{
    		ImageTTFText($label,24,0,$lmargin+245,$tmargin+127,$eblack,$arial,$result['blood_group']);
		}

	}else{ # Use system fonts
	
  		#  Full encounter nr
    	
		ImageString($label,4,2,2,$fen,$eblack);
		# Encounter admission date
    	#ImageString($label,2,2,18,$result['pdate'],$eblack);
		ImageString($label,2,2,18,$result['pdate'],$eblack);
				
		# Family name, first name
    	ImageString($label,5,10,40,$result['name_last'].', '.$result['name_first'],$eblack);
		# Date of birth
    	ImageString($label,3,10,55,$result['date_birth'],$eblack);
	
    	//for($a=0,$l=75;$a<sizeof($addr);$a++,$l+=15) ImageString($label,4,10,$l,$addr[$a],$eblack);
		# Address street nr, street name
#    	ImageString($label,4,10,75,strtoupper($result['addr_str']).' '.$result['addr_str_nr'],$eblack);
    	ImageString($label,4,10,75,ucfirst($result['street_name']).' '.$result['brgy_name'],$eblack);   # burn added : July 24, 2007
		# Address, zip, city/town name
#    	ImageString($label,4,10,90,strtoupper($result['addr_zip']).' '.$result['citytown_name'],$eblack);
    	ImageString($label,4,10,90,$result['mun_name'].' '.$result['zipcode'].' '.$result['prov_name'],$eblack);   # burn added : July 24, 2007
		# Sex
    	ImageString($label,5,10,125,strtoupper($result['sex']),$eblack);
		# Family name, repeat print
    	ImageString($label,5,30,125,$result['name_last'],$eblack);
		# Insurance co name
    	ImageString($label,4,10,140,$ins_obj->getFirmName($result['insurance_firm_id']),$eblack);
		# Location
		#edited by VAN 07-10-08
		if ($en)
    		ImageString($label,3,10,160,$locstr,$eblack);
		else
			ImageString($label,3,10,160,$locstr2.' '.$LDInsShortID[$result['insurance_class_nr']],$eblack);	
		#Blood group
		if(stristr('AB',$result['blood_group'])){
    		ImageString($label,5,257,125,$result['blood_group'],$eblack);
		}else{
    		ImageString($label,5,265,125,$result['blood_group'],$eblack);
		}
		
	}

	// place the barcode img
    if($bc) ImageCopy($label,$bc,110,4,9,9,170,37);

	if(!$child_img)
	{
    	Imagepng($label);
	
	// *******************************************************************
    // * comment the following one line if you want to deactivate caching of 
	// * the barcode label image
	// *******************************************************************
/*    
	// START
    Imagepng ($im,"../cache/barcodes/pn_".$pn."_bclabel_".$lang.".png");
	// END
*/	
	// Do not edit the following lines
    ImageDestroy($label);
	}
	else
	{
	  if(file_exists($root_path.'main/imgcreator/gd_test_request_'.$subtarget.'.php'))   include_once($root_path.'main/imgcreator/gd_test_request_'.$subtarget.'.php');
	  else Imagepng($label);
	/*   Imagepng($label);*/
	  
	}
?>
