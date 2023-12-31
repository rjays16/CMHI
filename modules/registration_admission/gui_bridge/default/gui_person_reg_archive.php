<?php

require('./gui_bridge/default/gui_std_tags.php');


function createTR($input_name, $ld_text, $input_val, $colspan = 2, $input_size = 35)
{

?>

<tr>
<td class="reg_item"><?php echo $ld_text ?>
</td>
<td colspan=<?php echo $colspan; ?> class="reg_input"><input name="<?php echo $input_name; ?>" type="text" size="<?php echo $input_size; ?>" value="<?php if(isset($input_val)) echo $input_val; ?>">
</td>
</tr>

<?php
}

# Start buffering

ob_start();

?>

<script  language="javascript">
<!--
function popSearchWin(target,obj_val,obj_name) {
	urlholder="./data_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
	DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
}

<?php require($root_path.'include/inc_checkdate_lang.php'); ?>

-->
</script>
<script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>

<!-- OLiframeContent(src, width, height) script:
 (include WIDTH with its parameter equal to width, and TEXTPADDING,0, in the overlib call)
-->
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/iframecontentmws.js"></script>

<!-- Core module and plugins:
-->
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_draggable.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_filter.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_overtwo.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_scroll.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_shadow.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/overlibmws/overlibmws_modal.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/NumberFormat154.js"></script>

<?php

	echo '<link rel="stylesheet" type="text/css" media="all" href="' .$root_path.'js/jscalendar/calendar-win2k-cold-1.css">';
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/calendar.js"></script>';
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/lang/calendar-en.js"></script>';
	echo '<script type="text/javascript" src="'.$root_path.'js/jscalendar/calendar-setup_3.js"></script>';
		# burn added: March 6, 2007
	echo '<script type="text/javascript" language="javascript" src="'.$root_path.'js/jsprototype/prototype.js"></script>';
	require("address.common.php");
	if ($xajax) {
		$xajax->printJavascript('../../classes/xajax');
	}
?>
<script type="text/javascript" language="javascript">

  function AddressWizard() {
    return overlib(
      OLiframeContent('<?= $root_path?>/modules/registration_admission/seg-address-select.php', 600, 410, 'fOrderTray', 0, 'auto'),
        WIDTH,600, TEXTPADDING,0, BORDER,0, 
        STICKY, SCROLL, CLOSECLICK, MODAL, 
        CLOSETEXT, '<img src=<?= $root_path ?>/images/close_red.gif border=0 >',
        CAPTIONPADDING,2, 
        CAPTION,'Address Wizard',
        MIDX,0, MIDY,0, 
        STATUS,'Run address wizard');
  }

  function jsEnableAddresses(en) {
    var enable = en=="1";
    $('region_nr').style.visibility = enable ? "visible" : "hidden";
    $('prov_nr').style.visibility = enable ? "visible" : "hidden";
    $('mun_nr').style.visibility = enable ? "visible" : "hidden";
    $('zipcode').style.visibility = enable ? "visible" : "hidden";
    $('brgy_nr').style.visibility = enable ? "visible" : "hidden";
    
    $('region_nr').disabled = !enable;
    $('prov_nr').disabled = !enable;
    $('mun_nr').disabled = !enable;
    $('zipcode').disabled = !enable;
    $('brgy_nr').disabled = !enable;
  }

  function ajxClearAddress(objName) {
    var optionsList;
    var el=$(objName);
    if (el) {
      optionsList = el.getElementsByTagName('OPTION');
      for (var i=optionsList.length-1;i>=0;i--) {
        optionsList[i].parentNode.removeChild(optionsList[i]);
      }
    }
  }/* end of function ajxClearAddress */

  function ajxAddAddress(objName, text, value) {
    var grpEl = $(objName);
    if (grpEl) {
      var opt = new Option( text, value );
      if (value == "NULL") opt.style.color = "#0000c0";
      grpEl.appendChild(opt);
    }
  }/* end of function ajxAddAddress */
    /*
        Resets the province's name, municipality/city's and 
        barangay's default name and zip code after selecting a region.
        input: region's ID
    */
  function setByRegion(regionID) {
    $('region_nr').value = regionID;
    $('prov_nr').value = -1;
    $('mun_nr').value = -1;
    $('zipcode').value = -1;
    $('brgy_nr').value = -1;
  }

  function jsSetRegion() {  
    var aRegion=$('region_nr');

    var aRegionID = aRegion.options[aRegion.selectedIndex].value;
    if (aRegionID==-1){
      /*
      xajax_setAll('province'); // resets the list of provinces
      xajax_setAll('municity'); // resets the list of municipalities/cities
      xajax_setAll('zipcode'); // resets the list of zipcodes
      xajax_setAll('barangay'); // resets the list of barangays
      */
      
      $('brgy_nr').disabled = true;
      ajxClearAddress('brgy_nr');
      ajxAddAddress('brgy_nr', '-No Baranggay Available-', -1);
      
      $('mun_nr').disabled = true;
      ajxClearAddress('mun_nr');
      ajxAddAddress('mun_nr', '-No Municipality/City Available-', -1);
      
      $('zipcode').disabled = true;
      ajxClearAddress('zipcode');
      ajxAddAddress('zipcode', '-No Zipcode Available-', -1);
      
      $('prov_nr').disabled = true;
      ajxClearAddress('prov_nr');
      ajxAddAddress('prov_nr', '-No Province Available-', -1);
      
    } else {
      $('region_nr').disabled = true;
      $('prov_nr').style.visibility = "hidden";
      $('mun_nr').style.visibility = "hidden";
      $('zipcode').style.visibility = "hidden";
      $('brgy_nr').style.visibility = "hidden";
      xajax_setRegion(aRegionID);
    }
  }
    /*
        Sets the region's name, province's name; and
        resets barangay's and municipality/city's default name 
        after selecting a province.
        input: region's ID, province's ID
    */
  function setByProvince(regionID, provID) {
    $('region_nr').value = regionID;
    $('prov_nr').value = provID;
    $('mun_nr').value = -1;
    $('zipcode').value = -1;
    $('brgy_nr').value = -1;
  }

  function jsSetProvince() {
    var aRegion=$('region_nr');
    var aRegionID = aRegion.options[aRegion.selectedIndex].value;
    var aProvince=$('prov_nr');
    var aProvinceID = aProvince.options[aProvince.selectedIndex].value;
    
    if (aProvinceID==-1) {
      /*
      xajax_setAll('municity',aRegionID); // resets the list of municipalities/cities
      xajax_setAll('zipcode',aRegionID);  // resets the list of zipcodes
      xajax_setAll('barangay',aRegionID); // resets the list of barangays
      */
      $('brgy_nr').disabled = true;
      ajxClearAddress('brgy_nr');
      ajxAddAddress('brgy_nr', '-No Baranggay Available-', -1);
      
      $('mun_nr').disabled = true;
      ajxClearAddress('mun_nr');
      ajxAddAddress('mun_nr', '-No Municipality/City Available-', -1);
      
      $('zipcode').disabled = true;
      ajxClearAddress('zipcode');
      ajxAddAddress('zipcode', '-No Zipcode Available-', -1);
      
    } else {
      $('region_nr').disabled = true;
      $('prov_nr').disabled = true;
      
      $('mun_nr').style.visibility = "hidden";
      $('zipcode').style.visibility = "hidden";
      $('brgy_nr').style.visibility = "hidden";
      xajax_setProvince(aProvinceID);
    }
  }
    /*
        Sets the region's name, province's name, municipality/city's name,
        zipcode; and resets barangay's default name after selecting a municipality/city.
        input: region's ID, province's ID, zipcode
    */
  function setByMuniCity(regionID, provID, munID, zipcode) {
    $('region_nr').value = regionID;
    $('prov_nr').value = provID;
    $('mun_nr').value = munID;
    $('zipcode').value = zipcode;
    $('brgy_nr').value = 'NULL';
  }

  function jsSetMuniCity() {
    var aRegion=$('region_nr');
    var aRegionID = aRegion.options[aRegion.selectedIndex].value;
    var aProvince=$('prov_nr');
    var aProvinceID = aProvince.options[aProvince.selectedIndex].value;
    var aMuniCity=$('mun_nr');
    var aMuniCityID = aMuniCity.options[aMuniCity.selectedIndex].value;
    
    if (aMuniCityID==-1){
      /*
      xajax_setAll('municity',-1,aProvinceID); // resets the list of municipalities/cities
      xajax_setAll('zipcode',-1,aProvinceID); // resets the list of zipcodes
      xajax_setAll('barangay',-1,aProvinceID); // resets the list of barangays
      */
      $('zipcode').value = -1;
      
      $('brgy_nr').disabled = true;
      ajxClearAddress('brgy_nr');
      ajxAddAddress('brgy_nr', '-No Baranggay Available-', -1);
    } else {
      $('region_nr').disabled = true;
      $('prov_nr').disabled = true;
      $('mun_nr').disabled = true;
      $('zipcode').disabled = true;
      $('brgy_nr').style.visibility = "hidden";
      xajax_setMuniCity(aMuniCityID);
    }
  }
  
    /*
        Sets the region's name, province's name, municipality/city's name; 
        and resets barangay's default name after selecting a zipcode.
        input: region's ID, province's ID, municipality/city ID
    */
  function setByZipcode(regionID, provID, munID, zipcode) {  
    $('region_nr').value = regionID;
    $('prov_nr').value = provID;
    $('mun_nr').value = munID;
    $('zipcode').value = zipcode;
    $('brgy_nr').value = 0;
  }

  function jsSetZipcode() {
    var aRegion=$('region_nr');
    var aRegionID = aRegion.options[aRegion.selectedIndex].value;
    var aProvince=$('prov_nr');
    var aProvinceID = aProvince.options[aProvince.selectedIndex].value;
    var aZipcode=$('zipcode');
    var aZipcodeID = aZipcode.options[aZipcode.selectedIndex].value;

    if (aZipcodeID==-1){
      /*
      xajax_setAll('municity',-1,aProvinceID); // resets the list of municipalities/cities
      xajax_setAll('zipcode',-1,aProvinceID); // resets the list of zipcodes
      xajax_setAll('barangay',-1,aProvinceID); // resets the list of barangays
      */
      
      $('mun_nr').value = -1;
      $('brgy_nr').disabled = true;
      ajxClearAddress('brgy_nr');
      ajxAddAddress('brgy_nr', '-No Baranggay Available-', -1);
    } else {
      $('region_nr').disabled = true;
      $('prov_nr').disabled = true;
      $('mun_nr').disabled = true;
      $('zipcode').disabled = true;
      
      $('brgy_nr').style.visibility = "hidden";
      xajax_setZipcode(aZipcodeID);
    }
  }
    /*
        This will set the region's name, province's name, municipality/city's name, 
        zipcode, and barangay's name after selecting a barangay.
        input: region's ID, province's ID, municipality/city ID, zipcode, brgyID
    */
  function setByBarangay(regionID, provID, munID, zipcode, brgyID) {
    $('region_nr').value = regionID;
    $('prov_nr').value = provID;
    $('mun_nr').value = munID;
    $('zipcode').value = zipcode;
    $('brgy_nr').value = brgyID;
  }

  function jsSetBarangay() {
    /*
    var aRegion=$('region_nr');
    var aRegionID = aRegion.options[aRegion.selectedIndex].value;
    var aProvince=$('prov_nr');
    var aProvinceID = aProvince.options[aProvince.selectedIndex].value;
    var aMuniCity=$('mun_nr');
    var aMuniCityID = aMuniCity.options[aMuniCity.selectedIndex].value;
    var aBrgy=$('brgy_nr');
    var aBrgyID = aBrgy.options[aBrgy.selectedIndex].value;
    
    
    $('region_nr').disabled = true;
    $('prov_nr').disabled = true;
    $('mun_nr').disabled = true;
    $('zipcode').disabled = true;
    $('brgy_nr').disabled = true;
    
    if (aBrgyID==-1){
      xajax_setAll('barangay',-1,-1,aMuniCityID); // resets the list of barangays    
    }else{
      xajax_setBarangay(aBrgyID);
    }
    */
  }

</script>

<?php

global $ptype, $allow_patient_register, $allow_newborn_register, $allow_er_user, $allow_opd_user, $allow_ipd_user, $allow_medocs_user, $allow_update;

$sTemp = ob_get_contents();
ob_end_clean();   # burn added: March 6, 2007

if (!isset($mode) || ($rows==0)){   # burn added: March 22, 2007
	$smarty->append('JavaScript',$sTemp);
}
# Start buffering
ob_start();    # burn added: March 6, 2007

# Empty the buffer variable
$sTemp = '';

/* Create the tabs */
$tab_bot_line='#66ee66';
require('./gui_bridge/default/gui_tabs_patreg.php');

if(isset($mode)&&($mode=='search'||$mode=='paginate')){

#    if(defined('SHOW_SEARCH_QUERY')&&SHOW_SEARCH_QUERY) echo $LDSearchKeyword.': '.$s2;   # burn commented: March 7, 2007
?>

<table border=0>
  <tr>
    <td><img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?> align="absmiddle"></td>
    <td class="prompt">
		<b>
		<?php 
			
#			if($rows) echo str_replace("~nr~",$totalcount,$LDFoundData).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.'; else echo str_replace('~nr~','0',$LDSearchFound); 
			if($rows) echo str_replace("~no.~",$totalcount,$LDFoundData).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.'; else echo str_replace('~no.~','0',$LDSearchFound); 
		?>
		</b>
	</td>
  </tr>
</table>

<?php
}
?>

<?php

if(isset($rows)&&$rows) {
	echo '
			<tr><td colspan=8>'.$pagen->makePrevLink($LDPrevious).'</td>
			<td align=right>'.$pagen->makeNextLink($LDNext).'</td>
			</tr>';
 ?>

<table border=0 cellpadding=0 cellspacing=1>
  <tr class="reg_list_titlebar">
      <td><b>
	  <?php
	  	if($oitem=='sex') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDSex,'sex',$odir,$flag); 
			 ?></b></td>
      <td><b>
	  <?php 
	  	if($oitem=='name_last') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDLastName,'name_last',$odir,$flag); 
			 ?></b></td>
      <td><b>
	  <?php 
	  	if($oitem=='name_first') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDFirstName,'name_first',$odir,$flag); 
			 ?></b></td>
      <td><b>
	  <?php 
	  
	  	if($oitem=='date_birth') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDBday,'date_birth',$odir,$flag); 
			 ?></b></td>
      <td align='center'><b>
		<?php 
		if($oitem=='brgy_name') $flag=TRUE;
			else $flag=FALSE;
		echo $pagen->SortLink('Barangay','brgy_name',$odir,$flag); 		 	
		?></b></td>
      <td align='center'><b>
		<?php 
		if($oitem=='mun_name') $flag=TRUE;
			else $flag=FALSE;
		echo $pagen->SortLink('Muni/City','mun_name',$odir,$flag); 		 	
		?></b></td>
      <td align='center'><b>
	  <?php 
#	  	if($oitem=='addr_zip') $flag=TRUE;
#			else $flag=FALSE;
#		 echo $pagen->SortLink($LDZipCode,'addr_zip',$odir,$flag); 		 	
		if($oitem=='zipcode') $flag=TRUE;
			else $flag=FALSE;
		echo $pagen->SortLink($LDZipCode,'zipcode',$odir,$flag); 		 	
		?></b></td>
      <td align="center"><b>
	  <?php 
	  	if($oitem=='pid') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDRegistryNr,'pid',$odir,$flag); 
			 ?></b></td>
      <td><b>
	  <?php 
	  	if($oitem=='date_reg') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDRegDate,'date_reg',$odir,$flag); 
			 ?></b></td>
  </tr>
<?php 
	# Load common icons
	$img_arrow=createComIcon($root_path,'r_arrowgrnsm.gif','0');
	$img_male=createComIcon($root_path,'spm.gif','0');
	$img_female=createComIcon($root_path,'spf.gif','0');

		# burn added: March 16, 2007
 	include_once($root_path.'include/care_api_classes/class_encounter.php');
	# Create encounter object
	$encounter_obj=new Encounter(); 
	
	#added by VAN 05-07-08
	include_once($root_path.'include/care_api_classes/class_person.php');
	# Create encounter object
	$person_obj=new Person(); 

		# burn added: March 16, 2007
	require_once($root_path.'include/care_api_classes/class_department.php');
	$dept_obj=new Department;
	
	#added by VAN 05-13-08
	require_once($root_path.'include/care_api_classes/class_social_service.php');
	$objSS = new SocialService;
		
	if (!empty($HTTP_SESSION_VARS['sess_login_userid']))
		$seg_user_name = $HTTP_SESSION_VARS['sess_login_userid'];
	else
		$seg_user_name = $HTTP_SESSION_VARS['sess_temp_userid'];
	$user_dept_info = $dept_obj->getUserDeptInfo($seg_user_name);
	
	$toggle=0;
	while($result=$ergebnis->FetchRow()){
		if($result['status']==''||$result['status']=='normal' || $result['status']=='NORMAL'){
	
				# burn added: March 16, 2007
			$label='';
			if ($enc_row = $encounter_obj->getLastestEncounter($result['pid'])){
				if($enc_row['encounter_type']==1){
					$label =	'<img '.createComIcon($root_path,'flag_red.gif').'>'.
								'<font size=1 color="red">ER</font>';
				}elseif($enc_row['encounter_type']==2){
					$label =	'<img '.createComIcon($root_path,'flag_blue.gif').'>'.
								'<font size=1 color="blue">Outpatient</font>';
				}else{
					$label =	'<img '.createComIcon($root_path,'flag_green.gif').'>'.
								'<font size=1 color="green">Inpatient</font>';
				}
			}else{
				$enc_row['encounter_type']=0;   # no ACTIVE encounter
			}
				# burn added: March 16, 2007
			if ( ($allow_opd_user) &&
				  (($enc_row['encounter_type']==0) || $enc_row['encounter_type']==2)
				){
				$allow_show_details=TRUE;   # search under OPD Triage
			}elseif( ($allow_er_user) &&
						(($enc_row['encounter_type']==0) || $enc_row['encounter_type']==1)
					 ){
				$allow_show_details=TRUE;   # search under ER Triage
			}elseif(($allow_ipd_user)||($allow_medocs_user)){
				$allow_show_details=TRUE;   # search under Admitting section or Medical Records
			}else{
				$allow_show_details=FALSE;   # User has no permission to VIEW person's details
			}

			echo'
			  <tr ';
			if($toggle){
				//echo "bgcolor=#efefef"; 
				echo 'class="wardlistrow2"';
				$toggle=0;
			} else {
				//echo "bgcolor=#ffffff";
				echo 'class="wardlistrow1"';
				$toggle=1;
			}
			
			$buf='patient_register_show.php'.URL_APPEND.'&origin=archive&pid='.$result['pid'].'&target=archiv&ptype='.$ptype;
			#added by VAN 05-06-08
			#echo "<br>id = ".$user_dept_info['id'];
			#print_r($user_dept_info);
			if ($allow_opd_user){
				$name = trim($result['name_first'])." ".trim($result['name_last']);
				$encounter_obj->getPatientOPDORNoforADay($result['pid'],$name);
				
				$patSS = $objSS->getPatientSocialClass($result['pid']);
				
				#$perInfo = $person_obj->searchIfEmployee($result['pid']);
				#echo "<br>count = ".$person_obj->count;
				#echo "sql = ".$encounter_obj->sql;
				#echo "<br>sc = ".$result['senior_ID'];
				#commented by VAN 10-11-08
                /*
                if (($encounter_obj->count)||($result['senior_ID'])||($result['personnelID'])||($patSS['parentid']=='D')){
					$allow_show_details=TRUE;   # search under OPD Triage and patient is already paid for current day
				}else{
					$allow_show_details=FALSE;   # search under OPD Triage
				}	
                 */
			}else{
				#commented by VAN 10-11-08        
                #$allow_show_details=TRUE; 
			}	
		    $allow_show_details=TRUE;				
			if ($allow_show_details){
				echo '>
					<td>&nbsp; &nbsp;<a href="'.$buf.'" title="'.$LDClk2Show.'">';
		
				switch($result['sex']){
					case 'f': echo '<img '.$img_female.'>'; break;
					case 'm': echo '<img '.$img_male.'>'; break;
					default: echo '&nbsp;'; break;
				}
		
				echo '</a></td>
				 <td>&nbsp; <a href="'.$buf.'" title="'.$LDClk2Show.'">'.$result['name_last'].'</a></td>
				 <td>&nbsp; &nbsp;<a href="'.$buf.'" title="'.$LDClk2Show.'">'.$result['name_first'].'</a>';
			}else{
				echo '>
					<td>&nbsp; &nbsp;';
		
				switch($result['sex']){
					case 'f': echo '<img '.$img_female.'>'; break;
					case 'm': echo '<img '.$img_male.'>'; break;
					default: echo '&nbsp;'; break;
				}
		
				echo '</td>
				 <td>&nbsp; '.$result['name_last'].'</td>
				 <td>&nbsp; &nbsp;'.$result['name_first'];			
			}		
			# If person is dead show a black cross
			if($result['death_date']&&$result['death_date']!=$dbf_nodate) 
				echo '&nbsp;<img '.createComIcon($root_path,'blackcross_sm.gif','0','absmiddle').'>';

				# burn added: March 27, 2007
			#edited by VAN 02-11-08
			#$date_birth = @formatDate2Local($zeile['date_birth'],$date_format);			
			$date_birth = @formatDate2Local($result['date_birth'],$date_format);			
			$bdateMonth = substr($date_birth,0,2);
			$bdateDay = substr($date_birth,3,2);
			$bdateYear = substr($date_birth,6,4);
			if (!checkdate($bdateMonth, $bdateDay, $bdateYear)){
				# invalid birthdate
				$date_birth='';
			}

#			 <td>&nbsp; &nbsp;'.@formatDate2Local($result['date_birth'],$date_format).'</td>
			
			if ($allow_show_details){
				echo '</td>
				 <td>&nbsp; &nbsp;'.$date_birth.'</td>
				 <td>&nbsp; &nbsp;'.$result['brgy_name'].'</td>
				 <td>&nbsp; &nbsp;'.$result['mun_name'].'</td>
				 <td align=right>&nbsp; &nbsp;'.$result['zipcode'].'</td>
				 <td>&nbsp; &nbsp;'.$result['pid'].''.$label.'</td>
				 <td align=right>&nbsp; &nbsp;<a href="'.$buf.'" title="'.$LDClk2Show.'">'.@formatDate2Local($result['date_reg'],$date_format).'</a></td>
			  </tr>
			  <tr>
			  <td colspan=10 height=1 class="thinrow_vspacer"><img src="../../gui/img/common/default/pixel.gif" border=0 width=1 height=1></td>
			  </tr>';
		  }else{
		  		echo '</td>
			 	<td>&nbsp; &nbsp;'.$date_birth.'</td>
				 <td>&nbsp; &nbsp;'.$result['brgy_name'].'</td>
				 <td>&nbsp; &nbsp;'.$result['mun_name'].'</td>
				 <td align=right>&nbsp; &nbsp;'.$result['zipcode'].'</td>
				 <td>&nbsp; &nbsp;'.$result['pid'].''.$label.'</td>
				 <td align=right>&nbsp; &nbsp;'.@formatDate2Local($result['date_reg'],$date_format).'</td>
			  </tr>
			  <tr>
			  <td colspan=10 height=1 class="thinrow_vspacer"><img src="../../gui/img/common/default/pixel.gif" border=0 width=1 height=1></td>
			  </tr>';
		  }
	#    <td align=right>&nbsp; &nbsp;'.$result['addr_zip'].'</td>
	
		}
	} # end of while loop

		echo '
			<tr><td colspan=8>'.$pagen->makePrevLink($LDPrevious).'</td>
			<td align=right>'.$pagen->makeNextLink($LDNext).'</td>
			</tr>';
		
 ?>
</table>
<p>

<form method="post"  action="patient_register_archive.php" >
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="mode" value="?">
<!--<input type="submit" value="<?php echo $LDAdvancedSearch ?>" >-->
                             </form>

<?php 
}
else
{
?>

<form method="post" action="<?php echo $thisfile; ?>" name="aufnahmeform">

<table border=0 cellspacing=0 cellpadding=0>

<?php
if(!isset($pid)) $pid='';
createTR('pid', $LDAdmitNr2,$pid);
if(!isset($user_id)) $user_id='';
createTR( 'user_id', $LDRegBy,$user_id);

	$phpfd=$date_format;
	
	$phpfd=str_replace("dd", "%d", strtolower($phpfd));
	$phpfd=str_replace("mm", "%m", strtolower($phpfd));
	$phpfd=str_replace("yyyy","%Y", strtolower($phpfd));
	//$phpfd=str_replace("yy","%Y", strtolower($phpfd));

?>

<tr>
<td class="reg_item"><?php echo $LDRegDate ?>
</td>
<td class="reg_input">
<input name="date_start" id="date_text" type="text" size=10 maxlength=10   value="<?php if(!empty($date_start)) echo @formatDate2Local($date_start,$date_format);  ?>"  onBlur="IsValidDate(this,'<?php echo $date_format ?>')" onKeyUp="setDate(this,'<?php echo $date_format ?>','<?php echo $lang ?>')">
 <!-- <a href="javascript:show_calendar('aufnahmeform.date_start','<?php echo $date_format ?>')"> -->
 <img <?php echo createComIcon($root_path,'show-calendar.gif','0','absmiddle'); ?> id="date_trigger" style="cursor:pointer ">
 <font size=1>[ <?php   
 $dfbuffer="LD_".strtr($date_format,".-/","phs");
  echo $$dfbuffer;
 ?> ] </font>
 
 	<!--EDITED: SEGWORKS -->
	<script type="text/javascript">
	Calendar.setup ({
		inputField : "date_text", ifFormat : "<?php echo $phpfd?>", showsTime : false, button : "date_trigger", singleClick : true, step : 1
	
	});
</script>
 
</td>
<td class="reg_input"><nobr><?php echo $LDTo ?>: <input name="date_end" id="date_text1" type="text" size=10 maxlength=10  value="<?php if(!empty($date_end))  echo @formatDate2Local($date_end,$date_format);  ?>"  onBlur="IsValidDate(this,'<?php echo $date_format ?>')" onKeyUp="setDate(this,'<?php echo $date_format ?>','<?php echo $lang ?>')">
<!-- <a href="javascript:show_calendar('aufnahmeform.date_end','<?php echo $date_format ?>')"> -->
 <img <?php echo createComIcon($root_path,'show-calendar.gif','0','absmiddle'); ?> id="date_trigger1" style="cursor:pointer ">
 <font size=1>[ <?php   
 $dfbuffer="LD_".strtr($date_format,".-/","phs");
  echo $$dfbuffer;
 ?> ] </font>
</nobr>
 	<!--EDITED: SEGWORKS -->
	<script type="text/javascript">
	Calendar.setup ({
		inputField : "date_text1", ifFormat : "<?php echo $phpfd?>", showsTime : false, button : "date_trigger1", singleClick : true, step : 1
	
	});
</script>

</td>
</tr>
<tr>
	<td colspan="3">&nbsp;
		
	</td>
</tr>
<tr>
	<td colspan="3">
		Personal Details:
	</td>
</tr>
<?php
if(!isset($name_last)) $name_last='';
if(!isset($name_first)) $name_first='';
createTR('name_last', $LDLastName,$name_last);
createTR( 'name_first', $LDFirstName,$name_first);

if (!$GLOBAL_CONFIG['person_name_2_hide'])
{
if(!isset($name_2)) $name_2='';
createTR('name_2', $LDName2,$name_2);
}

if (!$GLOBAL_CONFIG['person_name_3_hide'])
{
if(!isset($name_3)) $name_3='';
createTR('name_3', $LDName3,$name_3);
}

if (!$GLOBAL_CONFIG['person_name_middle_hide'])
{
if(!isset($name_middle)) $name_middle='';
createTR('name_middle', $LDNameMid,$name_middle);
}

if (!$GLOBAL_CONFIG['person_name_maiden_hide'])
{
if(!isset($name_maiden)) $name_maiden='';
createTR('name_maiden', $LDNameMaiden,$name_maiden);
}

if (!$GLOBAL_CONFIG['person_name_others_hide'])
{
if(!isset($name_others)) $name_others='';
createTR('name_others', $LDNameOthers,$name_others);
}

if(!isset($date_birth)) $date_birth='';
if(!isset($addr_str)) $addr_str='';
if(!isset($addr_str_nr)) $addr_str_nr='';
if(!isset($addr_zip)) $addr_zip='';
if(!isset($addr_city_town)) $addr_city_town='';
?>

<tr>
<td class="reg_item"><?php echo $LDBday ?>
</td>
<td class="reg_input">
<input name="date_birth" id="date_text2" type="text" size="15" maxlength=10 value="<?php  if(!empty($date_birth))  echo @formatDate2Local($date_birth,$date_format);  ?>"
 onFocus="this.select();"  onBlur="IsValidDate(this,'<?php echo $date_format ?>')" onKeyUp="setDate(this,'<?php echo $date_format ?>','<?php echo $lang ?>')"> 
<!-- <a href="javascript:show_calendar('aufnahmeform.date_birth','<?php echo $date_format ?>')"> -->
 <img <?php echo createComIcon($root_path,'show-calendar.gif','0','absmiddle'); ?> id="date_trigger2" style="cursor:pointer ">
 <font size=1>[ <?php   
 $dfbuffer="LD_".strtr($date_format,".-/","phs");
  echo $$dfbuffer;
 ?> ] </font>
 
  	<!--EDITED: SEGWORKS -->
	<script type="text/javascript">
	Calendar.setup ({
		inputField : "date_text2", ifFormat : "<?php echo $phpfd?>", showsTime : false, button : "date_trigger2", singleClick : true, step : 1
	
	});
</script>

 
</td>
<!--
<td colspan=2 class="reg_input"><?php echo $LDSex ?>: <input name="sex" type="radio" value="m"><?php echo $LDMale ?>&nbsp;&nbsp;
<input name="sex" type="radio" value="f"><?php echo $LDFemale ?>
</td>
-->
</tr>
<?php
if (!$GLOBAL_CONFIG['person_place_birth_hide'])
{
if(!isset($place_birth)) $place_birth='';
createTR('place_birth', $segBirthplace,$place_birth,2);
}
if (!$GLOBAL_CONFIG['Contact_No_hide'])
{
if(!isset($contactNo)) $contactNo='';
createTR('ContactNo', $segContactNo,$contactNo,2);
}
?>
<tr>
<td class="reg_item">
	<?php echo $LDSex ?>:
</td>
<td class="reg_input"> 
<input name="sex" type="radio" value="m"><?php echo $LDMale ?>&nbsp;&nbsp;
<input name="sex" type="radio" value="f"><?php echo $LDFemale ?>
</td>
</tr>
<tr>
<td class="reg_item"><?php echo $LDCivilStatus ?>
</td>
<td colspan=2 class="reg_input"> <input name="civil_status" type="radio" value="single"><?php echo $LDSingle ?>&nbsp;&nbsp;
<input name="civil_status" type="radio" value="married"><?php echo $LDMarried ?>
 <input name="civil_status" type="radio" value="divorced"><?php echo $LDDivorced ?>&nbsp;&nbsp;
<input name="civil_status" type="radio" value="widowed"><?php echo $LDWidowed ?>
 <input name="civil_status" type="radio" value="separated"><?php echo $LDSeparated ?>&nbsp;&nbsp;
</td>
</tr>
<?php
if (!$GLOBAL_CONFIG['person_religion_hide'])
{
if(!isset($religion)) $religion='';
createTR('religion', $LDReligion,$religion,2);
}

if (!$GLOBAL_CONFIG['person_phone_1_nr_hide'])
{
if(!isset($phone_1_nr)) $phone_1_nr='';
createTR('phone_1_nr', $LDPhone.' 1',$phone_1_nr,2);
}

if (!$GLOBAL_CONFIG['person_cellphone_1_nr_hide'])
{
if(!isset($cellphone_1_nr)) $cellphone_1_nr='';
createTR('cellphone_1_nr', $LDCellPhone.' 1',$cellphone_1_nr,2);
}
?>
<tr>
	<td colspan=3>&nbsp;</td>
</tr>

 <tr>
<td colspan=3><?php echo $LDAddress ?>:
</td>

</tr>

<tr>
<td colspan=3>
<?php
	# burn added: March 6, 2007
	
$sTemp_pre = ob_get_contents();
ob_end_clean();

require_once('address_new.php');
//$this->smarty->assign('segAddressNew',"$segAddressNew");
#echo "<br>segAddressNew = ".$segAddressNew;
$sTemp_pre.=$segAddressNew;

# Start buffering
ob_start();

?>
</td>
</tr>
<tr>
<td colspan="3">&nbsp;

</td>
</tr>

<!--

<tr>
<td class="reg_item"><?php echo $LDStreet ?>:
</td>
<td class="reg_input"><input name="addr_str" type="text" size="35" value="<?php if(isset($addr_str)) echo $addr_str; ?>">
</td>
<td class="reg_input">&nbsp;&nbsp;&nbsp;<?php if (isset($errorstreetnr)&&$errorstreetnr) echo "<font color=red>"; ?><?php echo $LDStreetNr ?>:<input name="addr_str_nr" type="text" size="10" value="<?php echo $addr_str_nr; ?>">
</td>
</tr>

<tr>
<td class="reg_item"><?php echo $LDTownCity ?>:
</td>
<td class="reg_input"><input name="addr_citytown_name" type="text" size="35" value="<?php if(isset($addr_citytown_name)) echo $addr_citytown_name; ?>">
<a href="javascript:popSearchWin('citytown','aufnahmeform.addr_citytown_nr','aufnahmeform.addr_citytown_name')"><img <?php echo createComIcon($root_path,'l-arrowgrnlrg.gif','0') ?>></a>
</td>
<td class="reg_input">&nbsp;&nbsp;&nbsp;<?php if (isset($errorzip)&&$errorzip) echo "<font color=red>"; ?><?php echo $LDZipCode ?>:<input name="addr_zip" type="text" size="10" value="<?php echo $addr_zip; ?>">
</td>
</tr>

-->

<?php

if ((!$GLOBAL_CONFIG['person_mother_name_hide']) || (!$GLOBAL_CONFIG['person_father_name_hide']) ||
	 (!$GLOBAL_CONFIG['person_spouse_name_hide']) || (!$GLOBAL_CONFIG['person_guardian_name_hide'])){
?>
				<tr>
					<td colspan=3>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">
						<?= $segFamilyBackground ?>
					</td>
				</tr>
<?php
}

/*
if (!$GLOBAL_CONFIG['person_mother_name_hide']){
	if(!isset($mother_name)) $mother_name='';
	createTR('mother_name', $segMotherName,$mother_name,2);
}
*/
if (!$GLOBAL_CONFIG['person_mother_name_hide']){
	if(!isset($mother_name)) $mother_name='';
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2">&nbsp;&nbsp;First Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Maiden Middle Name &nbsp;&nbsp; Maiden Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Last Name</td>
	</tr>
	<tr>
		<td class="reg_item"><?=$segMotherName?></td>
		<td colspan=2 class="reg_input"><input name="mother_fname" id="mother_fname" type="text" size="30" value=""><input name="mother_maidenname" id="mother_maidenname" type="text" value=""><input name="mother_mname" id="mother_mname" type="text" value=""><input name="mother_lname" id="mother_lname" type="text" size="25" value="">
		</td>
	</tr>
	
<?
	#createTR('mother_name', $segMotherName,$mother_name,2);
}


/*
if (!$GLOBAL_CONFIG['person_father_name_hide']){
	if(!isset($father_name)) $father_name='';
	createTR('father_name', $segFatherName,$father_name,2);
}
*/
if (!$GLOBAL_CONFIG['person_father_name_hide']){
	if(!isset($father_name)) $father_name='';
?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2">&nbsp;&nbsp;First Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Middle Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Last Name</td>
	</tr>
	<tr>
		<td class="reg_item"><?=$segFatherName?></td>
		<td colspan=2 class="reg_input"><input name="father_fname" id="father_fname" type="text" size="30" value=""><input name="father_mname" id="father_mname" type="text" value=""><input name="father_lname" id="father_lname" type="text" size="25" value="">
		</td>
	</tr>
	
<?	
	#createTR('father_name', $segFatherName,$father_name,2);
}


if (!$GLOBAL_CONFIG['person_spouse_name_hide']){
	if(!isset($spouse_name)) $spouse_name='';
	createTR('spouse_name', $segSpouseName,$spouse_name,2);
}
if (!$GLOBAL_CONFIG['person_guardian_name_hide']){
	if(!isset($guardian_name)) $guardian_name='';
	createTR('guardian_name', $segGuardianName,$guardian_name,2);
}

?>
<tr>
	<td colspan=3>&nbsp;</td>
</tr>
<?php


if (!$GLOBAL_CONFIG['person_phone_2_nr_hide'])
{
if(!isset($phone_2_nr)) $phone_2_nr='';
createTR('phone_2_nr', $LDPhone.' 2',$phone_2_nr,2);
}

if (!$GLOBAL_CONFIG['person_cellphone_2_nr_hide'])
{
if(!isset($cellphone_2_nr)) $cellphone_2_nr='';
createTR('cellphone_2_nr', $LDCellPhone.' 2',$cellphone_2_nr,2);
}

if (!$GLOBAL_CONFIG['person_fax_hide'])
{
if(!isset($fax)) $fax='';
createTR('fax', $LDFax,$fax,2);
}

if (!$GLOBAL_CONFIG['person_email_hide'])
{
if(!isset($email)) $email='';
createTR('email', $LDEmail,$email,2);
}

?>
<tr>
	<td colspan=3>&nbsp;</td>
</tr>
<tr>
	<td colspan=3>Other Personal Details:</td>
</tr>
<?php
if (!$GLOBAL_CONFIG['person_occupation_hide']){
	if(!isset($occupation)) $occupation='';
	#echo "occupation = ".$occupation;
	createTR('occupation', $LDOccupation,$occupation,2);
}

if (!$GLOBAL_CONFIG['person_citizenship_hide'])
{
if(!isset($citizenship)) $citizenship='';
createTR('citizenship', $LDCitizenship,$citizenship,2);
}

if (!$GLOBAL_CONFIG['person_ethnic_orig_hide'])
{
if(!isset($ethnic_orig)) $ethnic_orig='';
createTR('ethnic_orig', $LDEthnicOrigin,$ethnic_orig,2);
}

if (!$GLOBAL_CONFIG['person_sss_nr_hide'])
{
if(!isset($sss_nr)) $sss_nr='';
createTR('sss_nr', $LDSSSNr,$sss_nr,2);
}

if (!$GLOBAL_CONFIG['person_nat_id_nr_hide'])
{
if(!isset($nat_id_nr)) $nat_id_nr='';
createTR('nat_id_nr', $LDNatIdNr,$nat_id_nr,2);
}

?>

</table>
<p>
<input type=hidden name="sid" value=<?php echo $sid; ?>>
<input type=hidden name="lang" value="<?php echo $lang; ?>">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="addr_citytown_nr">
<input  type="image" <?php echo createLDImgSrc($root_path,'searchlamp.gif','0') ?> alt="<?php echo $LDSaveData ?>" align="absmiddle">

<input type="hidden" name="ptype" id="ptype" value="<?php echo $ptype; ?>" />
</form>

<script language="javascript">
<!-- 

var target;
target = '<?php echo $target ?>';
//alert('target = '+target);

<?php
		$stopFillUp=FALSE;
		if (!$brgy_nr && !$mun_nr && !$prov_nr && !$region_nr){
			$stopFillUp=TRUE;
?>			
			// added by VAN 02-11-08
			if (target=='archiv')
				xajax_setMuniCity(24);   // no city will be set
			else	
				xajax_setMuniCity(24); // sets Davao City as default city
<?php
		}
?>
<?php
		if ($brgy_nr && !$stopFillUp && $brgy_nr != 'NULL'){
			$stopFillUp=TRUE;
?>
			xajax_setBarangay(<?=$brgy_nr?>); // sets the default barangay
<?php
		}
?>
<?php
		if ($mun_nr && !$stopFillUp){
			$stopFillUp=TRUE;
?>
			xajax_setMuniCity(<?=$mun_nr?>); // sets the default city
<?php
		}
?>
<?php
		if ($prov_nr && !$stopFillUp){
			$stopFillUp=TRUE;
?>
			xajax_setProvince(<?=$prov_nr?>); // sets the default province
<?php
		}
?>
<?php
		if ($region_nr && !$stopFillUp){
?>
			xajax_setRegion(<?=$region_nr?>); // sets the default region			
<?php
		}elseif(!$stopFillUp){
?>
			xajax_setRegion(14,true); // sets Region XI as default region
<?php
		}
?>

/*
			xajax_setAll('region'); // resets the list of regions
			xajax_setAll('province'); // resets the list of provinces
			xajax_setAll('municity'); // resets the list of municipalities/cities
			xajax_setAll('zipcode'); // resets the list of zipcodes
			xajax_setAll('barangay'); // resets the list of barangays
*/
// -->
</script>
<?php
}

$sTemp = ob_get_contents();
ob_end_clean();
#$smarty->assign('sMainDataBlock',$sTemp);
$smarty->assign('sMainDataBlock',$sTemp_pre." ".$sTemp);


$smarty->assign('sMainBlockIncludeFile','registration_admission/reg_plain.tpl');

# Show mainframe

$smarty->display('common/mainframe.tpl');
?>

<!-- <img <?php echo createComIcon($root_path,'varrow.gif','0') ?>> <a href="patient_register.php<?php echo URL_APPEND; ?>&newdata=1&from=entry"><?php echo $LDPatientRegister ?></a><br>
<img <?php echo createComIcon($root_path,'varrow.gif','0') ?>> <a href="patient_register_search.php<?php echo URL_APPEND; ?>"><?php echo $LDPatientSearch ?></a><br>

 --><p>
<!--commented by VAN 04-17-08 -->
<!--<a href="<?php	echo 'patient.php'.URL_APPEND; ?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> alt="<?php echo $LDCancelClose ?>"></a>-->


