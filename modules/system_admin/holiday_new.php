<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/**
 * Segworks Technologies Corporation (c)2007
 * Hospital Information System
 * MLHE
 */
define('LANG_FILE','icd10icpm.php');
$local_user='aufnahme_user';

$thisfile='holiday_new.php';

require_once($root_path.'include/inc_front_chain_lang.php');

# Load the address object
require_once($root_path.'include/care_api_classes/class_holiday.php');
$holiday_obj=new Holiday;

//$db->debug=1;
switch($retpath)
{
	case 'list': $breakfile='holiday_list.php'.URL_APPEND.'&target=holiday'; break; 
	case 'search': $breakfile='holiday_search.php'.URL_APPEND.'&target=holiday'; break;
	default: $breakfile='edv-system_manage.php'.URL_APPEND.'&target=holiday';
}


if(!isset($mode)){
	$mode='';
	$edit=true;		
}else{
	switch($mode)
	{
		case 'save':
		{
			$day = $_POST['day'];
			$month = $_POST['month'];

			$holiday_obj->details = array('holiday'=>$_POST['holiday_name'],
								'dynamic_date'=> (date("Y")."-".$month."-".$day),
								'day'=>$day,
								'month'=>$month,
								'year'=>date("Y"),
								'create_id'=>$_SESSION['sess_user_name']);

			if($holiday_obj->selectHoliday()){ //check existing holiday
				echo 'Holiday Date Already Exists';
			}else{
				if($holiday_obj->insertHoliday()){
					$holiday_nr=$holiday_obj->LastInsertPK('id',$db->Insert_ID());
					header("location:holiday_info.php?sid=$sid&lang=$lang&holiday_nr=$holiday_nr&mode=show&save_ok=1&retpath=$retpath&day=$day&month=$month");
					exit;
				}
			}
			
			break;
		}//case
		
	} // end of switch($mode)
}//else


# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');
 
 # Title in toolbar
 $smarty->assign('sToolbarTitle',"New Holiday");

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('icpm_new.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"Holiday Manager");

# Coller Javascript code

 
ob_start();
?>

<script type="text/javascript">
<!--
 // insert javascript here.
function chkfld(d){
	if(d.holiday_name.value==""){
		alert("Please type the name of holiday");
		d.holiday_name.focus();
		return false;
	}
	return true;
}


//-->
</script>


<?php

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

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
&nbsp;<br>

<form action="<?php echo $thisfile; ?>" method="post" name="icpm" onSubmit="return chkfld(this);">
<font face="Verdana, Arial" size=-1><?php echo $LDEnterAllFields ?></font>
<table border=0>
  <tr>
    <td align=right class="adm_item"><font color=#ff0000><b>*</b></font> Name of Holiday : </td>
    <td class="adm_input">
   		<input type="text" id="holiday_name" name="holiday_name" size="50" value="<?php echo $_POST['holiday_name'];?>">
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

			    	if($_POST['month'] == $i){
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

			    	if($_POST['day'] == $i){
			    		$selected = "selected";
			    	}

					echo '<option value="'.$i.'" '.$selected.'>'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>';
				}
			?>
		</select>
	</td>
  </tr>
  <tr>
    <td class=pblock><input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>></td>
    <td  align=right><a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> border="0"></a></td>
  </tr>
</table>
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="mode" value="save">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="retpath" value="<?php echo $retpath ?>">
</form>

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