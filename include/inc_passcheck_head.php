<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (eregi('inc_passcheck_head.php',$PHP_SELF)) 
	die('<meta http-equiv="refresh" content="0; url=../">');
/*------end------*/
?>
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
 <TITLE></TITLE>
 
<script language="javascript">
<!-- 
function pruf(d)
{
	if((d.userid.value=="")&&(d.keyword.value=="")) 
	   return false;
	//else
		//alert("pruf");	
		//self.opener.location.href=self.opener.location.href;
}

// -->
</script>
 
 <?php 
require($root_path.'include/inc_js_gethelp.php');
include($root_path.'include/inc_css_a_hilitebu.php');
?>
 
</HEAD>
