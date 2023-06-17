<?php
/**
 * Segworks Technologies Corporation 2007 
 * GNU General Public License 
 * Copyright (C)2007 
 * MHLE  SELECT * FROM hisdb.care_icd10_en c LIMIT 0,1000
 */

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('../ICPM/roots.php');
require($root_path.'include/inc_environment_global.php');

define('LANG_FILE','icd10icpm.php');
$local_user='aufnahme_user';
require_once($root_path.'include/inc_front_chain_lang.php');
# Load the insurance object
require_once($root_path.'include/care_api_classes/class_holiday.php');
$holiday_obj=new Holiday;

switch($retpath)
{
	case 'list': $breakfile='holiday_list.php'.URL_APPEND.'&target=holiday'; break; 
	case 'search': $breakfile='holiday_search.php'.URL_APPEND.'&target=holiday'; break;
	default: $breakfile='edv-system_manage.php'.URL_APPEND.'&target=holiday';
}


$holiday_nr = $_GET['holiday_nr'];
$holiday_obj->details = array('id'=>$holiday_nr);

if(isset($holiday_nr)&&$holiday_nr){
	#echo "<br>mode=".$mode;
	#echo "phic = ".$phic;
	if(isset($_POST['mode'])&&$_POST['mode']=='update'){
		$holiday = $_POST['holiday_name'];
		$day = $_POST['day'];
		$month = $_POST['month'];
		$holiday_nr = $_POST['id'];

		$holiday_obj->details = array('holiday'=>$_POST['holiday_name'],
								'dynamic_date'=> (date("Y")."-".$month."-".$day),
								'day'=>$day,
								'month'=>$month,
								'year'=>date("Y"),
								'modify_id'=>$_SESSION['sess_user_name'],
								'id'=>$holiday_nr);

		if($holiday_obj->selectHoliday($holiday_nr)){
			echo 'Holiday Date Already Exists';
		}else{
			if($holiday_obj->updateHoliday()){
					header("location:holiday_info.php?sid=$sid&lang=$lang&holiday_nr=$holiday_nr&mode=show&save_ok=1&retpath=$retpath&day=$day&month=$month");
					exit;
			}else{
				echo 'Unable to update holiday';
			}
		}
	}elseif($row=$holiday_obj->getHolidayNr()){			
		if(is_object($row)){		
			$refcode=$row->FetchRow();
			extract($refcode);
		}
	}
}else{
	//Redirect to search function	
}
#echo "sql = ".$person_obj->sql;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');

# Title in toolbar
 $smarty->assign('sToolbarTitle',"Holiday Manager");
 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('icd10_update.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"Holiday Manager");

# Buffer page output

ob_start();

?>

<ul>
<?php
if(!empty($mode)){ 
?>
<table border=0>
  <tr>
    <td><img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?>></td>
    <td valign="bottom"><br><font class="warnprompt"><b>
	</b></font><p>
</td>
  </tr>
</table>
<?php 
} 
?>
<script language="javascript">
<!--
function chkfld(d){
	if(d.holiday_name.value==""){
		alert("Please type the name of holiday");
		d.holiday_name.focus();
		return false;
	}
	return true;
}

// -->
</script>

<form action="<?php echo $thisfile; ?>" method="post" name="icpm"  onSubmit="return chkfld(this);">
<table border=0> 
  <tr>
    <td align=right class="adm_item"><font color=#ff0000><b>*</b></font> Name of Holiday : </td>
    <td class="adm_input">
   		<input type="text" id="holiday_name" name="holiday_name" size="50" value="<?php echo $holiday;?>">
	</td>    
  <tr>
  <tr>
    <td align=right class="adm_item"><font color=#ff0000><b>*</b></font> Month : </td>
    <td class="adm_input">
   		<select id="month" name="month">
   			<?php
   				$month_options = array('','Jan', 'Feb', 'Mar', 'April', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
			    for( $i = 1; $i <= 12; $i++ ) {
			    	$selected = "";

			    	if($month == $i){
			    		$selected = "selected";
			    	}

			        echo '<option value="'.$i.'" '.$selected.'>'.$month_options[$i].'</option>';
			    }
   			?>
   		</select>
	</td>    
   </tr>
   <tr>
	<td align=right class="adm_item"> Day : </td>
	<td class="adm_input">
		<select id="day" name="day">
			<?php
				for($i=1; $i<=31; $i++){
					$selected = "";

			    	if($day == $i){
			    		$selected = "selected";
			    	}

					echo '<option value="'.$i.'" '.$selected.'>'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>';
				}
			?>
		</select>
	</td>
  </tr>    
  <tr>
    <td><input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>></td>
    <td  align=right><a href="<?php echo $breakfile;?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?>></a></td>
  </tr>
  </table>
  
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="id" value="<?php echo $holiday_nr ?>">
<input type="hidden" name="retpath" value="<?php echo $retpath ?>">
</form>
<p>

</ul>
<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);
 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>