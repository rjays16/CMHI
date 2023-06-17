<?php
$encounter_nr = $_GET['encounter_nr'] ? $_GET['encounter_nr'] : $_POST['encounter_nr'];

require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/inc_date_format_functions.php');

require_once($root_path.'include/care_api_classes/class_person.php');
$person=new Person;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$encounter= new Encounter();

if($_POST['is_submitted']){
	if($encounter->updateManualCoverage($encounter_nr, $_POST['allcoverage'])){
		echo "Succefully updated coverage.";
	}else{
		echo "Unable to update coverage";
	}
}

//Get Manual Coverage from Database
$all_amount = $encounter->getManualCoverage($encounter_nr);

?>

<form method="POST">
 	<table border=0 cellpadding=1 cellspacing=1 align="center" >
		<tr class="adm_item">
			<td bgcolor="C0D5BF"><FONT color="#000066" font-style='arial'>PHIC Coverage</td>
			<td><input color="#000066" type='text' id="allcoverage" name='allcoverage' value="<?=$all_amount?>"/></td>
		</tr>
		<tr>
			<td colspan='2' align="center">
				<input  height="23" width="72" type="image" src="../../gui/img/control/default/en/en_savedisc.gif">
			</td>
		</tr>
	</table>
	
	<input type="hidden" name="encounter_nr" id="encounter" value="<?=$encounter_nr?>"/>
	<input type="hidden" name="isallcoverage" id="isallcoverage" value="<?=$all_amount?>"/>
	<input type="hidden" name="is_submitted" id="is_submitted" value="1"/>
</form>