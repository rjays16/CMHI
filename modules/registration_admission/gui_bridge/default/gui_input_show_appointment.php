<script language="javascript">
<!-- Script Begin
function chkForm(d) {
	if(d.date.value==''){
		alert("<?php echo $LDPlsEnterDate; ?>");
		d.date.focus();
		return false;
	}else if(d.to_dept_nr.value==''){
		alert("<?php echo $LDPlsSelectDept; ?>");
		d.to_dept_nr.focus();
		return false;
	}else if(d.to_personell_name.value==''){
		alert("<?php echo $LDPlsEnterDoctor; ?>");
		d.to_personell_name.focus();
		return false;
	}else if(d.purpose.value==''){
		alert("<?php echo $LDPlsEnterPurpose; ?>");
		d.purpose.focus();
		return false;
	}else{
		return true;
	}
}
//  Script End -->
</script>
<?php
#
# If date was in the past, show error message
#
if($bPastDateError) echo '<font class="warnprompt">'.$LDInvalidDate.' '.$LDNoPastDate.'</font>';

?>
<form method="post" name="appt_form" onSubmit="return chkForm(this)">
 <table border=0 cellpadding=2 width=100%>
   <tr bgcolor="#f6f6f6">
     <td><font color="red"><b>*</b><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDDate; ?></td>
     <td>
		 <?
				 	$phpfd=$date_format;
	
					$phpfd=str_replace("dd", "%d", strtolower($phpfd));
					$phpfd=str_replace("mm", "%m", strtolower($phpfd));
					$phpfd=str_replace("yyyy","%Y", strtolower($phpfd));
					//$phpfd=str_replace("yy","%Y", strtolower($phpfd));
		 ?>
		 <input type="text" name="date" id="date_text" size=10 maxlength=10  
	 	 value="<?php
         if(!empty($date)&&($date!=$dbf_nodate)){
             if($error) echo $date;
					elseif($mode!='update') echo @formatDate2Local($date,$date_format);
         }
          ?>"
	 	onBlur="IsValidDate(this,'<?php echo $date_format ?>')" onKeyUp="setDate(this,'<?php echo $date_format ?>','<?php echo $lang ?>')">
		<!--<a href="javascript:show_calendar('appt_form.date','<?php echo $date_format ?>')">-->
 		<img <?php echo createComIcon($root_path,'show-calendar.gif','0','absmiddle',TRUE); ?> id="date_trigger" style="cursor:pointer "> 
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
   </tr>
   <tr bgcolor="#f6f6f6">
     <td></font><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDTime; ?></td>
     <td><input type="text" name="time" size=10 maxlength=10 value="<?php if(!empty($time)) echo convertTimeToLocal($time); ?>"></td>
   </tr>
   <tr bgcolor="#f6f6f6">
     <td><font color="red"><b>*</b><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDDepartment; ?></td>
     <td>
	    <select name="to_dept_nr">
		<option value=""></option>
	<?php
		
		while(list($x,$v)=each($deptarray)){
			echo '
				<option value="'.$v['nr'].'" ';
			if($v['nr']==$to_dept_nr) echo 'selected';
			echo ' >';
			if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
				else  echo $v['name_formal'];
			echo '</option>';
		}
	?>
        </select>
	 </td>
   </tr>
   
   <tr bgcolor="#f6f6f6">
     <td><font color="red"><b>*</b><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo "$LDPhysician/$LDClinician"; ?></td>
     <td><input type="text" name="to_personell_name" size=50 maxlength=60  value="<?php if(isset($to_personell_name)) echo $to_personell_name; ?>"></td>
   </tr>

   <tr bgcolor="#f6f6f6">
     <td><font color="red"><b>*</b><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDPurpose; ?></td>
     <td><textarea name="purpose" cols=40 rows=6 wrap="physical"><?php if(isset($purpose)) echo $purpose; ?></textarea>
         </td>
   </tr>
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDUrgency; ?></td>
     <td><FONT SIZE=-1  FACE="Arial" color="#000066">
	 		<input type="radio" name="urgency" value="0" <?php if($urgency==0) echo 'checked'; ?>><?php echo $LDNormal; ?>	
			<input type="radio" name="urgency" value="3" <?php if($urgency==3) echo 'checked'; ?>><?php echo $LDPriority; ?>
	 		<input type="radio" name="urgency" value="5" <?php if($urgency==5) echo 'checked'; ?>><?php echo $LDUrgent; ?>	
			<input type="radio" name="urgency" value="7" <?php if($urgency==7) echo 'checked'; ?>><?php echo $LDEmergency; ?>
     </td>
   </tr>
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDRemindPatient; ?> ?</td>
     <td><FONT SIZE=-1  FACE="Arial" color="#000066">
	 		<input type="radio" name="remind" value="1"  <?php if($remind) echo 'checked'; ?>> <?php echo $LDYes; ?>	<input type="radio" name="remind" value="0"   <?php if(!$remind) echo 'checked'; ?>> <?php echo $LDNo; ?>
     </td>
   </tr>
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDRemindBy; ?></td>
     <td><FONT SIZE=-1  FACE="Arial" color="#000066">
	 	<input type="checkbox" name="remind_email" value="1"   <?php if($remind_email) echo 'checked'; ?>><?php echo $LDEmail; ?>
	 	<input type="checkbox" name="remind_phone" value="1"  <?php if($remind_phone) echo 'checked'; ?>><?php echo $LDPhone; ?>
	 	<input type="checkbox" name="remind_mail" value="1"  <?php if($remind_mail) echo 'checked'; ?>><?php echo $LDMail; ?>
	 </td>
   </tr>
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDPlannedEncType; ?></td>
     <td><FONT SIZE=-1  FACE="Arial" color="#000066">
<?php
if(is_object($encounter_classes)){
    while($result=$encounter_classes->FetchRow()) {
?>
		<input name="encounter_class_nr" type="radio"  value="<?php echo $result['class_nr']; ?>" <?php if($encounter_class_nr==$result['class_nr']) echo 'checked'; ?>>
<?php 
        $LD=$result['LD_var'];
        if(isset($$LD)&&!empty($$LD)) echo $$LD; else echo $result['name'];
        echo '&nbsp;';
	}
} 
?>
     </td>
   </tr>

 </table>
<input type="hidden" name="encounter_nr" value="<?php echo $HTTP_SESSION_VARS['sess_en']; ?>">
<input type="hidden" name="pid" value="<?php echo $HTTP_SESSION_VARS['sess_pid']; ?>">
<?php
if($mode=='select'){
?>
<input type="hidden" name="nr" value="<?php echo $nr; ?>">
<?php
}
?>

<input type="hidden" name="mode" value="<?php if($mode=='select') echo 'update'; else echo 'create';?>">
<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>

</form>
