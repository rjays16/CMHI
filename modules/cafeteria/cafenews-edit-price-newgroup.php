<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('./roots.php');
require_once($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
define('LANG_FILE','editor.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/inc_front_chain_lang.php');

$breakfile='cafenews.php'.URL_APPEND;
$returnfile='cafenews-edit-price-select.php'.URL_APPEND;

$dbtable='care_cafe_prices';

if(($groupname)&&($mode=='save'))
{
	$sql="INSERT INTO $dbtable (lang,article,description) VALUES ('$lang','$groupname','group')";

	if($ergebnis=$db->Execute($sql))
	{
		header("Location: cafenews-edit-price.php?sid=$sid&lang=$lang&mode=saved_newgroup&groupname=$groupname");
		exit;
	}
		else echo "<p>".$sql."<p>$LDDbNoSave";
}

?>
<?php html_rtl($lang); ?>
<!-- Generated by AceHTML Freeware http://freeware.acehtml.com -->
<!-- Creation date: 21.12.2001 -->
<head>
<?php echo setCharSet(); ?>
<title></title>


<script language="javascript">
function chkForm(d)
{
	if(d.groupname.value) return true;
		else return false;
}
</script>

<?php require($root_path.'include/inc_css_a_hilitebu.php'); ?>

</head>
<body>
<FONT  SIZE=8 COLOR="#cc6600">
<a href="javascript:editcafe()"><img <?php echo createComIcon($root_path,'basket.gif','0') ?>></a> <b><?php echo $LDCafePrices ?></b></FONT>
<hr>
<form name="selectform" action="cafenews-edit-price-newgroup.php" onSubmit="return chkForm(this)">
<table border=0>
  <tr>
    <td><img <?php echo createMascot($root_path,'mascot1_r.gif','0') ?>></td>
    <td colspan=2><FONT  SIZE=4 COLOR="#000066">
			<?php echo $LDEnterGroup ?>
	</td>
  </tr>

    <td>&nbsp;</td>
    <td bgcolor="ccffff" colspan=2>
		&nbsp;<?php echo $LDProdGroup ?>:<br>
		&nbsp;<input type="text" name="groupname" size=40 maxlength=40>
  <br><p>
  </td>
  </tr>
  <tr>
   <td>
	<a href="<?php echo $returnfile ?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0') ?>></a>
   </td>
     <td >
<input type="image" <?php echo createLDImgSrc($root_path,'continue.gif','0') ?>>
  </td>
    <td align=right >
	<a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?>></a>
  </td>
  </tr>
</table>
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="mode" value="save">
</form></body>
</html>
