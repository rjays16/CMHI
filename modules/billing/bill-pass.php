<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables=array('departments.php');
define('LANG_FILE','stdpass.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/inc_front_chain_lang.php');

/* Set the allowed area basing on the target */
require_once($root_path.'global_conf/areas_allow.php');
$allowedarea=&$allow_area['admit'];

#echo "labor_test_request_pass.php : 1 target = '".$target."' <br> \n";
#echo "bil-pass.php : 1 allowedarea = "; print_r($allowedarea); echo " <br> \n";

#if(!isset($target)||!$target) $target='chemlabor';

# Set the origin
if(!isset($user_origin)||empty($user_origin)) $user_origin='aufnahme';

/* Set the default file forward */
#$fileforward=$root_path."modules/nursing/nursing-station-patientdaten-doconsil-".$target.".php".URL_REDIRECT_APPEND."&noresize=1&user_origin=".$user_origin."&target=".$target;
$fileforward=$root_path."modules/billing/billing-main.php".URL_REDIRECT_APPEND."&noresize=1&user_origin=".$user_origin."&target=".$target;
$thisfile='bill-pass.php';

# Set the breakfile
if($user_origin == 'bill') $breakfile = "billing-main-menu.php".URL_APPEND; 

$test_pass_logo='monitor2.gif';
//$userck='ck_lab_user';
$userck='aufnahme_user';

//echo "$target $subtarget";
switch($target)
{	
  	case 'seg_promissory_note':
		$title = "Billing - Promissory Note";
		$breakfile = "bill-main-menus.php";
		$allowedarea=array("_a_1_billmanage","_a_2_billviewsave");
		$fileforward = $root_path."modules/billing_new/billing-promissory-note.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;		
		break;

	case 'seg_billing':
		$title = "Billing - Billing Services";
		$breakfile = "bill-main-menu.php";
		$allowedarea=array("_a_1_billmanage","_a_2_billviewsave");
		$fileforward = "billing-main.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;		
		break;

	//Francis 11-08-13
	case 'seg_billing_nPHIC':
		$title = "Billing - Billing Services";
		$breakfile = "bill-main-menu.php";
		$allowedarea=array("_a_1_billmanage","_a_2_billviewsave");
		$fileforward = "billing-main.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;		
		break;

	case 'seg_billing_PHIC':
		$title = "Billing - Billing Services";
		$breakfile = "bill-main-menu.php";
		$allowedarea=array("_a_1_billmanage","_a_2_billviewsave");
		$fileforward = $root_path."modules/billing_new/billing-main-new.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;		
		break;
	//end Francis
		
	case 'seg_billing_list':
		$title = "Billing - List of Billed Patients";
		$allowedarea=array("_a_2_billingList");
		$breakfile = "bill-main-menu.php";
		$fileforward = "billing-list.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;
		break;
    
    //Added by Genesis D. Ortiz 05-22-2014
	case 'seg_billing_list_patients':
		$title = "Billing - List of Patients";
		$allowedarea=array("_a_2_billingListPatient");
		$breakfile = "bill-main-menu.php";
		$fileforward = $root_path."modules/registration_admission/list_of_patients.php".URL_REDIRECT_APPEND."&user_origin=billing";
		break;
	//end Genesis D. Ortiz
			
	//change this 	
	case 'seglabservadmin':
			$title="Laboratory - Laboratory Services";
			$breakfile="labor.php".URL_APPEND;
			$fileforward="seg-lab-services-admin.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;
			#$fileforward="seg-labservices-manage.php".URL_APPEND;
			#echo "fileforward = $fileforward";
			break;
	//change this 
	case 'seg_billing_transmittal':
			$title="Billing - Transmittal";
			$allowedarea=array("_a_1_billtransmittal");
			$breakfile = "bill-main-menu.php";
			$fileforward="billing-transmittal.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;
			break;	
            
    case 'seg_transmittal_history':
            $title="Billing - Transmittal History";
            $allowedarea=array("_a_1_billtransmittal");
            $breakfile = "bill-main-menu.php";
            $fileforward="billing-transmittal-hist.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;
            break;    
                         
    case 'post_claim':                               
        $title="Billing - Post Claim";
        $allowedarea=array("_a_1_billaddclaim");
        $breakfile = "bill-main-menu.php";
        $fileforward="claim-posting.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;           
        break;
        
    case 'post_hist':
        $title="Billing - Claims History";
        $allowedarea=array("_a_1_billviewclaims");
        $breakfile = "bill-main-menu.php";
        $fileforward="claim-posting-hist.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;       
        break;
                         
	case 'packagemanage':
		$title="Billing - Transmittal";
		$allowedarea=array("_a_1_billtransmittal");
		$breakfile = "bill-main-menu.php";
		$fileforward=$root_path."modules/under_construction/hello.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin."&from=billing";
	break;
	//pol start
	case 'seg_billing_reports':
		$title= "Billing::Reports";
		$allowedarea=array("_a_1_billreports");
		$breakfile = "bill-main-menu.php";
		$fileforward = "billing_reports.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;
	break;
	// pol end

    //Added by Jarel 07/18/2014
    case 'company_billing':
        $title= "Billing::Company";
        $allowedarea=array("_a_1_billreports");
        $breakfile = "bill-main-menu.php";
        $fileforward = $root_path."modules/billing_new/billing-company-main.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin."&c_type=".$_GET['c_type'];
        break;

    case 'company_manager':
        $title= "Billing::Company Manager";
        $allowedarea=array("_a_1_billreports");
        $breakfile = "bill-main-menu.php";
        $fileforward = $root_path."modules/company/comp-manager.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;
        break;
    // Jarel end

    //added by mai 09-11-2014
    case 'seg_billing_promissory':
    	$title= "Billing::Process Promissory Note";
        $allowedarea=array("_a_1_billreports");
        $breakfile = "bill-main-menu.php";
        $fileforward = $root_path."modules/billing_new/billing-promissory-note.php".URL_REDIRECT_APPEND."&user_origin=".$user_origin;
    	break;   
    //end added by mai
    default :
  			$title=$LDTestRequest." - ".$LDTestType[$target];
}# end of switch stmt
					  
$lognote="$title ok";
if(isset($_GET['jasperPrint']) && $_GET['jasperPrint']==1)
	$fileforward .= "&jasperPrint=1";
//reset cookie;
// reset all 2nd level lock cookies
setcookie($userck.$sid,'');
require($root_path.'include/inc_2level_reset.php'); setcookie('ck_2level_sid'.$sid,'');
require($root_path.'include/inc_passcheck_internchk.php');
/*
echo "labor_test_request_pass.php : HTTP_SESSION_VARS : <br>\n"; print_r($HTTP_SESSION_VARS); echo" <br> \n";
echo "labor_test_request_pass.php : target = '".$target."' <br> \n";
echo "labor_test_request_pass.php : allowedarea = "; print_r($allowedarea); echo " <br> \n";
echo "labor_test_request_pass.php : userck = '".$userck."' <br> \n";
echo "labor_test_request_pass.php : 1 pass = '".$pass."' <br> \n";
*/
if ($pass=='check'){
/*
echo "labor_test_request_pass.php : 2 pass = '".$pass."' <br> \n";
echo "labor_test_request_pass.php : 2 fileforward = '".$fileforward."' <br> \n";
echo"labor_test_request_pass.php : 2 _GET : <br> \n"; print_r($_GET); echo" <br> \n";
*/
	include($root_path.'include/inc_passcheck.php');
}

$errbuf=$title;
$minimal=1;
require_once($root_path.'include/inc_config_color.php');
require($root_path.'include/inc_passcheck_head.php');

#echo "labor_test_request_pass.php : 3 pass = '".$pass."' <br> \n";
?>

<BODY onLoad="if (window.focus) window.focus(); document.passwindow.userid.focus();">
<FONT    SIZE=-1  FACE="Arial">
<!--replaced, 2007-10-05 FDP--------------
<P>
<img <?php echo createComIcon($root_path,$test_pass_logo,'0','absmiddle') ?>><FONT  COLOR="<?php echo $cfg[top_txtcolor] ?>"  size=5 FACE="verdana"> <b><?php echo $title;  ?></b></font>
<p>
-----with this--------------------------->
<table cellspacing="0"  class="titlebar" border=0>
	<tr valign=top  class="titlebar" >
  		<td bgcolor="#e4e9f4" valign="bottom">
		    &nbsp;&nbsp;
			<img <?php echo createComIcon($root_path,$test_pass_logo,'0','absmiddle') ?>>
			<font color="<?php echo $cfg[top_txtcolor] ?>"  size=6  face="verdana"> <b><?php echo $title ?></b></font>
		</td>
	</tr>
</table>
<!----until here only, 2007-10-05 FDP--->

<table width=100% border=0 cellpadding="0" cellspacing="0"> 

<?php require($root_path.'include/inc_passcheck_mask.php') ?>  

<p>

<?php
require($root_path.'include/inc_load_copyrite.php');

?>
</FONT>


</BODY>
</HTML>
