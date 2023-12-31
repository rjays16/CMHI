<?php
/**
* SegHIS Delivery History ....
*/
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');

define('NO_2LEVEL_CHK',1);
define('LANG_FILE','products.php');
$local_user='ck_inventory_user';

require_once($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'modules/inventory/ajax/seg-trans-list.common.php');

$GLOBAL_CONFIG=array();
# Create global config object
require_once($root_path.'include/care_api_classes/class_globalconfig.php');
require_once($root_path.'include/inc_date_format_functions.php');

require_once($root_path.'include/care_api_classes/class_access.php');        
require_once($root_path.'include/care_api_classes/class_department.php');
require_once($root_path.'include/care_api_classes/curl/class_curl.php');

$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
if($glob_obj->getConfig('date_format')) $date_format=$GLOBAL_CONFIG['date_format'];
$date_format=$GLOBAL_CONFIG['date_format'];
$phpfd=$date_format;
$phpfd=str_replace("dd", "%d", strtolower($phpfd));
$phpfd=str_replace("mm", "%m", strtolower($phpfd));
$phpfd=str_replace("yyyy","%Y", strtolower($phpfd));
$phpfd=str_replace("yy","%y", strtolower($phpfd));

$php_date_format = strtolower($date_format);
$php_date_format = str_replace("dd","d",$php_date_format);
$php_date_format = str_replace("mm","m",$php_date_format);
$php_date_format = str_replace("yyyy","Y",$php_date_format);
$php_date_format = str_replace("yy","y",$php_date_format);

if($_GET['from']=='phs' || $_POST['from']=='phs')
    $breakfile=$root_path."modules/phs/seg-phs-function.php".URL_APPEND."&userck=$userck";
else
    $breakfile=$root_path."modules/supply_office/seg-supply-functions.php".URL_APPEND."&userck=$userck";  

//$db->debug=1;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');
 
 if (!isset($_GET["list"])) $_GET["list"] = 'deliveries';
 if (isset($_POST["list"])) $_GET["list"] = $_POST["list"];
 switch ($_GET["list"])  {
     case 'deliveries':
        $sTitle = 'Deliveries';
        $listtyp = 0;
        break;
        
     case 'requests':
        $sTitle = 'Requests';
        $listtyp = 1; 
        break;
     
     case 'issuances':
        $sTitle = 'Issuances';
        $listtyp = 2; 
        break;
        
     case 'external_requests':
        $sTitle = 'External Requests';
        $listtyp = 3; 
        break;
        
     case 'pending_requests':
        $sTitle = 'Pending Requests';
        $listtyp = 4; 
        break;

     case 'adjustments':
        $sTitle = 'Adjustments';
        $listtyp = 5; 
        break;
 }    

 # Title in the title bar
 $smarty->assign('sToolbarTitle',"Inventory::$sTitle Posted");

 # href for the back button
// $smarty->assign('pbBack',$returnfile);

 # href for the help button
# $smarty->assign('pbHelp',"javascript:gethelp('products_db.php','search','$from','$cat')");
 #$smarty->assign('pbHelp',"javascript:gethelp('billing_main.php')");

 # href for the close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"List of $sTitle Posted");

 # Assign Body Onload javascript code
 $smarty->assign('sOnLoadJs','onLoad="selrecordOnChange();"'); 
     
 # Collect javascript code
 ob_start();

?>
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

<script type="text/javascript">
<!--
OLpageDefaults(BGCLASS,'olbg', CGCLASS,'olcg', FGCLASS,'olfg',
 CAPTIONFONTCLASS,'olcap', CLOSEFONTCLASS,'olclo', TEXTFONTCLASS,'oltxt');
//-->
</script>

<style type="text/css">
<!--
.olbg {
    background-image:url("<?= $root_path ?>images/bar_05.gif");
    background-color:#ffffff;
    border:1px outset #3d3d3d;
}
.olcg {
    background-color:#ffffff; 
    background-image:url("<?= $root_path ?>images/bar_05.gif");
    text-align:center;
}
.olcgif {background-color:#333399; text-align:center;}
.olfg {
    background-color:#ffffff; 
    text-align:center;
}
.olfgif {background-color:#bbddff; text-align:center;}
.olcap {
    font-family:Arial; font-size:13px; 
    font-weight:bold; 
    color:#708088;
}
a.olclo {font-family:Verdana; font-size:11px; font-weight:bold; color:#ddddff;}
a.olclo:hover {color:#ffffff;}
.oltxt {font-family:Arial; font-size:12px; color:#000000;}

a {color:#338855;font-weight:bold;}
a:hover {color:#FF00FF;}
.text12 {font-family:Verdana,Arial,sans-serif; font-size:12px;}
.text14 {font-family:Verdana,Arial,sans-serif; font-size:14px;}
.text16 {font-family:Verdana,Arial,sans-serif; font-size:16px;}
.text18 {font-family:Verdana,Arial,sans-serif; font-size:18px;}

.myHeader {font-family:Verdana,Arial,Helvetica,sans-serif;font-size:22px;}
.mySubHead {font-family:Verdana,Arial,Helvetica,sans-serif;font-size:18px;}
.mySpacer {font-family:Verdana,Arial,Helvetica,sans-serif;font-size:4px;}
.myText {font-family:Verdana,Arial,Helvetica,sans-serif;font-size:13px;color:#000000;}
.snip {font-family:Verdana,Arial,Helvetica;font-size:10px;}
.purple14 {font-family:Verdana,Arial,Helvetica,sans-serif;font-size:14px;color:purple;
 font-weight:bold;}
.purple18 {font-family:Verdana,Arial,Helvetica,sans-serif;font-size:18px;color:purple;
 font-weight:bold;font-style:italic;}
.yellow {color:#ffff00;}
.red {color:#cc0000;}
.blue {color:#0000cc;}

.tabFrame {
    margin:5px;
}
-->
</style> 

<script type="text/javascript" src="<?=$root_path?>js/jsprototype/prototype.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/fat/fat.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?= $root_path ?>js/jscalendar/calendar-win2k-cold-1.css" />
<script type="text/javascript" src="<?=$root_path?>js/jscalendar/calendar.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/jscalendar/calendar-setup_3.js"></script>
<script type="text/javascript" src="<?=$root_path?>js/NumberFormat154.js"></script>
<script language="javascript" type="text/javascript">
<!--
    function pSearchClose() {
        cClick();
    }
    
    function disableNav() {
        with ($('pageFirst')) {
            className = 'segDisabledLink'
            setAttribute('onclick','')
        }
        with ($('pagePrev')) {
            className = 'segDisabledLink'
            setAttribute('onclick','')
        }
        with ($('pageNext')) {
            className = 'segDisabledLink'
            setAttribute('onclick','')
        }
        with ($('pageLast')) {
            className = 'segDisabledLink'
            setAttribute('onclick','')
        }
    }

    var djConfig = { isDebug: true };
    var FIRST_PAGE=1, PREV_PAGE=2, NEXT_PAGE=3, LAST_PAGE=4, SET_PAGE=0;
    
    function jumpToPage(jumptype, page) {
        var form1 = document.forms[0];
    
        switch (jumptype) {
            case FIRST_PAGE:
                $('jump').value = 'first';
            break;
            case PREV_PAGE:
                $('jump').value = 'prev';
            break;
            case NEXT_PAGE:
                $('jump').value = 'next';
            break;
            case LAST_PAGE:
                $('jump').value = 'last';
            break;
            case SET_PAGE:
                $('jump').value = page;
            break;
        }        
                        
        form1.submit();
    }
    
    function deleteRecord(id) {
        var dform = document.forms[0]
        $('delete').value = id
        dform.submit()
    }
    
    function validate() {
        return true;
    }
    
    function keepFilters(noption) {
        var filter = '';        
        
        if (noption == 0) {
            if ($('chkspecific').checked) {
                var opt = $('selrecord').options[$('selrecord').selectedIndex];
                filter = $(opt.value).value;                
                xajax_updateFilterOption(0, $('selrecord').value);
                xajax_updateFilterTrackers($('selrecord').value, filter);
            }
            else
                xajax_updateFilterOption(0);
        }
        else {                    
            if ($('chkdate').checked) {
                if ($('seldate').value == 'specificdate') {
                    filter = $('specificdate').value;
                }
                if ($('seldate').value == 'between') {
                    filter = new Array($('between1').value, $('between2').value);
                }        
                    
                xajax_updateFilterOption(1, $('seldate').value);
                xajax_updateFilterTrackers($('seldate').value, filter);    
            }
            else
                xajax_updateFilterOption(1);
        }
        clearPageTracker();    
    }        
    
    function keepPage() {
        var pg = $('page').value;
        xajax_updatePageTracker(pg);
    }    
    
    function clearPageTracker() {
        xajax_clearPageTracker();
    }           
        
-->
</script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

$xajax->printJavascript($root_path.'classes/xajax_0.5'); 

switch ($listtyp) {
    case 0:
        include($root_path."include/care_api_classes/inventory/class_delivery.php");
        $objdelivery = new Delivery();
        $title_sufx = 'Deliveries Posted';   
        break;
        
    case 1:
        include($root_path."include/care_api_classes/inventory/class_request.php");
        $objrqst = new Request();
        $title_sufx = 'Requests Posted';   
        break;
    
    case 2:
        include($root_path."include/care_api_classes/inventory/class_issuance.php");
        $objissuance = new Issuance();
        $title_sufx = 'Issuances Posted';   
        break;
        
    case 3:
        include($root_path."include/care_api_classes/inventory/class_external_request.php");
        $objextreq = new SegExternalRequest();
        $title_sufx = 'External Requests Posted';   
        break;
        
    case 4:
        include($root_path."include/care_api_classes/inventory/class_request.php");
        $objrqst = new Request();
        $title_sufx = 'Pending Requests Posted';   
        break;
        
    case 5:
        include($root_path."include/care_api_classes/inventory/class_adjustment.php");
        $objadjs = new SegAdjustment();
        $title_sufx = 'Adjustments Posted';   
        break;
}

if (!$_POST["applied"]) {
    $keyname = "";
    
    if (isset($_SESSION["filteroption"])) {
        if (isset($_SESSION["filteroption"][0])) $_REQUEST["chkspecific"] = (($keyname = $_SESSION["filteroption"][0]) != '');
        if (isset($_SESSION["filteroption"][1])) $_REQUEST["chkdate"] = (($keyname = $_SESSION["filteroption"][1]) != '');
    }

    if (isset($_SESSION["filtertype"])) {
        if (isset($_SESSION["filteroption"][0])) {
            $_REQUEST["selrecord"] = $_SESSION["filtertype"][$keyname];
            $_REQUEST[strtolower($_SESSION["filtertype"][$keyname])] = $_SESSION["filter"][$keyname];
        }
            
        if (isset($_SESSION["filteroption"][1])) { 
            $_REQUEST["seldate"] = $_SESSION["filtertype"][$keyname];            
            if (is_array($_SESSION["filter"][$keyname])) {
                $_REQUEST["between1"] = $_SESSION["filter"][$keyname][0];
                $_REQUEST["between2"] = $_SESSION["filter"][$keyname][1];
            }
            else
                if ($_SESSION["filter"][$keyname] != "") 
                    $_REQUEST["specificdate"] = $_SESSION["filter"][$keyname];            
        }    
    }
    else { 
        if (is_null($_SESSION["filteroption"])) $_REQUEST['chkdate'] = true;
            
        $_REQUEST["seldate"] = "today";
    }
}

if (isset($_SESSION["current_page"])) {
    $_REQUEST['page'] = $_SESSION["current_page"];
}
        
if ($_POST['delete']) {
    $objCurl = new Rest_Curl();

    switch ($listtyp) { 
        case 0:            
            if ($objdelivery->delDelivery($_POST['delete'])) {
                $objCurl->deleteGRN($_POST['delete']);
                $sWarning = 'Delivery successfully deleted!';
            }
            else {
                global $db;
                $sWarning = 'Error in deletion of delivery: '.$db->ErrorMsg();
            }
            break;
            
        case 1:
            if ($objrqst->delRequest($_POST['delete'])) {
                $sWarning = 'Request successfully deleted!';
            }
            else {
                global $db;
                $sWarning = 'Error in deletion of request: '.$db->ErrorMsg();
            }
            break;
            
         case 2:
            if ($objissuance->delIssuance($_POST['delete'])) {
                $sWarning = 'Issuance successfully deleted!';
            }
            else {
                global $db;
                $sWarning = 'Error in deletion of issuance: '.$db->ErrorMsg();
            }            
//---------------------------- commented out by LST ---- 11.19.2009 -----------------------------------------------------------------------         
//            include($root_path."include/care_api_classes/inventory/class_inventory.php");
//            $objinventory = new Inventory();
//            
//            
//            if($result = $objissuance->getIssuanceHeader($_POST['delete'])){
//                $area_involved = $result["src_area_code"];
//                $issue_date = $result["issue_date"];
//                if ($resultDetails = $objissuance->getIssuanceDetails($_POST['delete'])) { 
//                    if ($resultDetails->RecordCount()>0) {
//                        while ($rowDetails = $resultDetails->FetchRow()) {  
//                            
//                            $objinventory->setInventoryParams($rowDetails["item_code"],$area_involved);
//                            
//                            if($rowDetails['expiry_date']!="-"){
//                                $objinventory->addInventory($rowDetails['item_qty'], $rowDetails['unit_id'], $rowDetails['expiry_date'], NULL,$issue_date); 
//                            }  
//                            else if($rowDetails['serial_no']!="-"){
//                                $objinventory->addInventory($rowDetails['item_qty'], $rowDetails['unit_id'], NULL, $rowDetails['serial_no'],$issue_date);     
//                            }  
//                            else {
//                                $objinventory->addInventory($rowDetails['item_qty'], $rowDetails['unit_id'], NULL, NULL,$issue_date);
//                            } 
//                        } 
//                    }
//                }
//            }
//                    
//            if ($objissuance->delIssuance($_POST['delete'])) {
//                $sWarning = 'Issuance successfully deleted!';
//                if ($savetry = $objissuance->delIssServed($_POST['delete'])) {
//                    $sWarning = 'Issuance and served requests successfully deleted!';
//                }
//                else {
//                    global $db;
//                    $sWarning = 'Error in deletion of issuance served: '.$db->ErrorMsg();
//                }   
//            }
//            else {
//                global $db;
//                $sWarning = 'Error in deletion of issuance: '.$db->ErrorMsg();
//            }
//---------------------------- commented out by LST ---- 11.19.2009 -----------------------------------------------------------------------
            break;
            
        case 3:
            if ($objextreq->delExternalRequest($_POST['delete'])) {
                $sWarning = 'External Request successfully deleted!';
            }
            else {
                global $db;
                $sWarning = 'Error in deletion of external request: '.$db->ErrorMsg();
            }
            break;
            
        case 4:
            if ($objrqst->delRequest($_POST['delete'])) {
                $sWarning = 'Request successfully deleted!';
            }
            else {
                global $db;
                $sWarning = 'Error in deletion of request: '.$db->ErrorMsg();
            }
            break;
            
        case 5:
            if ($objrqst->delAdjustment($_POST['delete'])) {
                $sWarning = 'Adjustment successfully deleted!';
            }
            else {
                global $db;
                $sWarning = 'Error in deletion of request: '.$db->ErrorMsg();
            }
            break;
    }
}

if ($_REQUEST['chkdate']) {
    switch(strtolower($_REQUEST["seldate"])) {
        case "today":
            $search_title = "Today's $title_sufx";
            $filters['DATETODAY'] = "";
        break;
        case "thisweek":
            $search_title = "This Week's $title_sufx";
            $filters['DATETHISWEEK'] = "";
        break;
        case "thismonth":
            $search_title = "This Month's $title_sufx";
            $filters['DATETHISMONTH'] = "";
        break;
        case "specificdate":
            $search_title = "$title_sufx On " . date("F j, Y",strtotime($_REQUEST["specificdate"]));
            $dDate = date("Y-m-d",strtotime($_REQUEST["specificdate"]));                
            $filters['DATE'] = $dDate;
        break;
        case "between":
            $search_title = "$title_sufx From " . date("F j, Y",strtotime($_REQUEST["between1"])) . " To " . date("F j, Y",strtotime($_REQUEST["between2"]));
            $dDate1 = date("Y-m-d",strtotime($_REQUEST["between1"]));
            $dDate2 = date("Y-m-d",strtotime($_REQUEST["between2"]));
            $filters['DATEBETWEEN'] = array($dDate1,$dDate2);
        break;
    }
}

if ($_REQUEST['chkspecific']) {
    switch(strtolower($_REQUEST["selrecord"])) {
        case "name":
            $search_title =  "$title_sufx by ".$_REQUEST["name"];
            $filters["NAME"] = $_REQUEST["name"];
        break;
        case "ref_no":
            $search_title =  "$title_sufx with no. ".$_REQUEST["ref_no"]; 
            $filters["REF_NO"] = $_REQUEST["ref_no"];
        break;
        case "item_desc":
            $search_title =  "$title_sufx with ".$_REQUEST["item_desc"];  
            $filters["ITEM_DESC"] = $_REQUEST["item_desc"];
        break;
    }
}

//if ($_REQUEST['chkarea']) {
//    $filters["AREA"] = $_REQUEST["selarea"];
//}

$current_page = $_REQUEST['page'];
if (!$current_page) $current_page = 0;
$list_rows = 15;
switch (strtolower($_REQUEST['jump'])) {
    case 'last':
        $current_page = $_REQUEST['lastpage'];
    break;
    case 'prev':
        if ($current_page > 0) $current_page--;
    break;
    case 'next':
        if ($current_page < $_REQUEST['lastpage']) $current_page++;
    break;
    case 'first':
        $current_page=0;
    break;
}

$_SESSION["current_page"] = $current_page;
/*
$objaccess = new Access();    
$dept_nr = $objaccess->getDeptNr($_SESSION['sess_temp_userid']);

$objdept = new Department();
$depscomma = $objdept->getChildrenDept($dept_nr);     
if (empty($depscomma))
    $result = $objdept->getAreasInDept($dept_nr);
else
    $result = $objdept->getAreasInADept($depscomma);  
*/
$obj = new Access();    
$dept_nr = $obj->getDeptNr($_SESSION['sess_temp_userid']); 

$per_arr = explode(" ", $HTTP_SESSION_VARS['sess_permission']);

if (in_array("System_Admin", $per_arr) || in_array("_a_0_all", $per_arr)) $dept_nr = "''"; 

$objdept = new Department();

$depscomma = $dept_nr;

#$subdepar = $objdept->getSubDept($dept_nr);  
$qry = "SELECT fn_get_children_dept(".$dept_nr.") as dps";
$rs = $db->Execute($qry);

if($rs){
    $row =  $rs->FetchRow();
    $depscomma = $row["dps"];
    if (empty($depscomma)){
        $result = $objdept->getAreasInDept($dept_nr);   
    }
    else{
            //modified by bryan 112609
            $depscomma = $depscomma.",".$dept_nr;
            $result = $objdept->getAreasInADept($depscomma);
    }
}            
  
$str_areas = '';          
if ($result) {
    while($row=$result->FetchRow()) {
        $str_areas .= (($str_areas == '') ? "" : "','").$row['area_code'];
    }
    if ($str_areas != '') $str_areas = "'".$str_areas."'"; 
}

switch ($listtyp) {
    case 0:
        $result = $objdelivery->getPostedDeliveries($filters, $list_rows * $current_page, $list_rows, $str_areas);            
        $rows = "";
        $last_page = 0;
        $count=0;    
        if ($result) {
            $rows_found = $objdelivery->FoundRows();
            if ($rows_found) {
                $last_page = floor($rows_found / $list_rows);
                $first_item = $current_page * $list_rows + 1;
                $last_item = ($current_page+1) * $list_rows;
                if ($last_item > $rows_found) $last_item = $rows_found;
                $nav_caption = "Showing ".number_format($first_item)."-".number_format($last_item)." out of ".number_format($rows_found)." record(s)";
            }
            
            while ($row = $result->FetchRow()) {
                $printUrl = $root_path . 'modules/supply_office/reports/stock_receive.php?refno=' . $row["refno"];
                $records_found = TRUE;        
                
                $btns = "<td align=\"right\" nowrap=\"nowrap\">
                            <a title=\"Edit\" href=\"seg-delivery.php".URL_APPEND."&userck=$userck&target=Edit&refno=".$row["refno"]."&from=list&src=Inventory\"><img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_edit.gif\" border=\"0\" align=\"absmiddle\" /></a>
                            <a title=\"Delete\" href=\"#\">
                            <img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_delete.gif\" border=\"0\" align=\"absmiddle\" onclick=\"if (confirm('Delete this delivery?')) deleteRecord('".$row["refno"]."')\"/>
                            </a>
                             <a title=\"Print\" target = '_blank' href={$printUrl}><img class='segSimulatedLink' src='../../images/btn_printpdf.gif' border='0' align='absmiddle'></a></td>";

                $rows .= "<tr class=\"$class\">
                              <td width=\"6%\">".$row["refno"]."</td>
                              <td width=\"7%\" align=\"center\">".strftime("%m-%d-%Y %I:%M%p",strtotime($row["receipt_date"]))."</td>
                              <td width=\"14%\">".$row["remarks"]."</td>                     
                              <td width=\"12%\">".$row["received_by"]."</td>
                              <td width=\"48%\">".$row["particulars"]."</td>
                              <td width=\"10%\" align=\"right\">".number_format($row["amount"],2,'.',',')."</td>".$btns."</tr>\n";                      
                $count++;                                                                            
            }    
        }
        else {
            print_r($result);
            $rows .= '        <tr><td colspan="7">'.$objdelivery->sql.'</td></tr>';
        }

        if (!$rows) {
            $records_found = FALSE;
            $rows .= '        <tr><td colspan="7">No deliveries at this time</td></tr>';
        }
        break;
        
    case 1:
        $result = $objrqst->getPostedRequests($filters, $list_rows * $current_page, $list_rows, $str_areas);                   
        $rows = "";
        $last_page = 0;
        $count=0;    
        if ($result) {
            $rows_found = $objrqst->FoundRows();
            if ($rows_found) {
                $last_page = floor($rows_found / $list_rows);
                $first_item = $current_page * $list_rows + 1;
                $last_item = ($current_page+1) * $list_rows;
                if ($last_item > $rows_found) $last_item = $rows_found;
                $nav_caption = "Showing ".number_format($first_item)."-".number_format($last_item)." out of ".number_format($rows_found)." record(s)";
            }
            
            while ($row = $result->FetchRow()) {       
                $records_found = TRUE;        
                
                $btns = "<td align=\"right\" nowrap=\"nowrap\">
                            <img class=\"segSimulatedLink\" src=\"".$root_path."images/btn_printpdf.gif\" border=\"0\" align=\"absmiddle\" onclick=\"window.open('".$root_path."modules/supply_office/pdf_reqiss_slipbyrequest.php?refno=".$row["refno"]."',null,'height=600,width=870,status=yes,toolbar=no,menubar=no,location=no,resizable=yes');\"/>   
                            <a title=\"Edit\" href=\"".$root_path."modules/supply_office/seg-supply-office-req.php".URL_APPEND."&userck=$userck&target=Edit&refno=".$row["refno"]."&from=list&src=Inventory\"><img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_edit.gif\" border=\"0\" align=\"absmiddle\" /></a>
                            <a title=\"Delete\" href=\"#\">
                            <img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_delete.gif\" border=\"0\" align=\"absmiddle\" onclick=\"if (confirm('Delete this request?')) deleteRecord('".$row["refno"]."')\"/>
                            </a></td>";                                            
                        
                $rows .= "<tr class=\"$class\">
                              <td width=\"6%\">".$row["refno"]."</td>
                              <td width=\"7%\" align=\"center\">".strftime("%m-%d-%Y %I:%M%p",strtotime($row["request_date"]))."</td>
                              <td width=\"12%\">".$row["requesting_area"]."</td>                     
                              <td width=\"12%\">".$row["requested_area"]."</td>
                              <td width=\"12%\">".$row["requestor"]."</td>
                              <td width=\"48%\">".$row["particulars"]."</td>".$btns."</tr>\n";                      
                $count++;                                                                            
            }    
        }
        else {
            print_r($result);
            $rows .= '        <tr><td colspan="7">'.$objrqst->sql.'</td></tr>';
        }

        if (!$rows) {
            $records_found = FALSE;
            $rows .= '        <tr><td colspan="7">No requests at this time</td></tr>';
        }    
        break;
        
    case 2:
        $result = $objissuance->getPostedIssuances($filters, $list_rows * $current_page, $list_rows, $str_areas);            
        $rows = "";
        $last_page = 0;
        $count=0;    
        if ($result) {
            $rows_found = $objissuance->FoundRows();
            if ($rows_found) {
                $last_page = floor($rows_found / $list_rows);
                $first_item = $current_page * $list_rows + 1;
                $last_item = ($current_page+1) * $list_rows;
                if ($last_item > $rows_found) $last_item = $rows_found;
                $nav_caption = "Showing ".number_format($first_item)."-".number_format($last_item)." out of ".number_format($rows_found)." record(s)";
            }
            
            while ($row = $result->FetchRow()) {       
                $records_found = TRUE;   
                
                $hasEquip = $objissuance->checkIfHavingEquipment($row["refno"]);     
                
                $btns = "<td align=\"right\" nowrap=\"nowrap\"><a title=\"Edit\" href=\"".$root_path."modules/supply_office/seg-issuance-edit.php".URL_APPEND."&userck=$userck&target=Edit&refno=".$row["refno"]."&from=list\"></a><img class=\"segSimulatedLink\" src=\"".$root_path."images/btn_printpdf.gif\" border=\"0\" align=\"absmiddle\" onclick=\"window.open('".$root_path."modules/supply_office/pdf_reqiss_slip.php?refno=".$row["refno"]."',null,'height=600,width=870,status=yes,toolbar=no,menubar=no,location=no,resizable=yes');\"/>";
                            
                if($row["status"] == 2 || $row["status"] == 3){
                    if($hasEquip) $btns .= "</a><img class=\"segSimulatedLink\" src=\"".$root_path."gui/img/common/default/pharma_equip.png\" border=\"0\" align=\"absmiddle\" onclick=\"window.open('".$root_path."modules/supply_office/pdf_equipment_acknowledgment.php?refno=".$row["refno"]."',null,'height=600,width=870,status=yes,toolbar=no,menubar=no,location=no,resizable=yes');\"/></td>";
                    #else
                    #$btns .= "</a><img class=\"segSimulatedLink\" src=\"".$root_path."images/btn_printpdf.gif\" border=\"0\" align=\"absmiddle\" onclick=\"window.open('".$root_path."modules/supply_office/pdf_reqiss_slip.php?refno=".$row["refno"]."',null,'height=600,width=870,status=yes,toolbar=no,menubar=no,location=no,resizable=yes');\"/></td>";
                }  
                else $btns .=  // "<img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_edit.gif\" border=\"0\" align=\"absmiddle\" /></a>
                            "<a title=\"Delete\" href=\"#\">
                            <img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_delete.gif\" border=\"0\" align=\"absmiddle\" onclick=\"if (confirm('Delete this issuance?')) deleteRecord('".$row["refno"]."')\"/>";
                            
                $btns .=     "</a></td>";                                            
                
                if($row["status"] == 2 || $row["status"] == 3) {
                     $rows .= "<tr class=\"$class\" >
                              <td width=\"6%\"><font color='red'>".$row["refno"]."</font></td>
                              <td width=\"7%\" align=\"center\"><font color='red'>".strftime("%m-%d-%Y %I:%M%p",strtotime($row["issue_date"]))."</font></td>
                              <td width=\"12%\"><font color='red'>".$row["issuing_area"]."</font></td>                     
                              <td width=\"12%\"><font color='red'>".$row["issued_area"]."</font></td>
                              <td width=\"12%\"><font color='red'>".$row["issued_by"]."</font></td>
                              <td width=\"48%\"><font color='red'>".$row["particulars"]."</font></td>".$btns."</tr>\n";
                }        
                else{
                    $rows .= "<tr class=\"$class\" >
                              <td width=\"6%\">".$row["refno"]."</td>
                              <td width=\"7%\" align=\"center\">".strftime("%m-%d-%Y %I:%M%p",strtotime($row["issue_date"]))."</td>
                              <td width=\"12%\">".$row["issuing_area"]."</td>                     
                              <td width=\"12%\">".$row["issued_area"]."</td>
                              <td width=\"12%\">".$row["issued_by"]."</td>
                              <td width=\"48%\">".$row["particulars"]."</td>".$btns."</tr>\n";
                }
                                     
                $count++;                                                                            
            }    
        }
        else {
            print_r($result);
            $rows .= '        <tr><td colspan="7">'.$objissuance->sql.'</td></tr>';
        }

        if (!$rows) {
            $records_found = FALSE;
            $rows .= '        <tr><td colspan="7">No issuances at this time</td></tr>';
        }    
        break;
        
    case 3:
        $result = $objextreq->getPostedExternalRequests($filters, $list_rows * $current_page, $list_rows, $str_areas);            
        $rows = "";
        $last_page = 0;
        $count=0;    
        if ($result) {
            $rows_found = $objextreq->FoundRows();
            if ($rows_found) {
                $last_page = floor($rows_found / $list_rows);
                $first_item = $current_page * $list_rows + 1;
                $last_item = ($current_page+1) * $list_rows;
                if ($last_item > $rows_found) $last_item = $rows_found;
                $nav_caption = "Showing ".number_format($first_item)."-".number_format($last_item)." out of ".number_format($rows_found)." record(s)";
            }
            
            while ($row = $result->FetchRow()) {       
                $records_found = TRUE;        
                
                $btns = "<td align=\"right\" nowrap=\"nowrap\"><img class=\"segSimulatedLink\" src=\"".$root_path."images/btn_printpdf.gif\" border=\"0\" align=\"absmiddle\" onclick=\"window.open('".$root_path."modules/supply_office/pdf_external_request.php?refno=".$row["refno"]."',null,'height=600,width=870,status=yes,toolbar=no,menubar=no,location=no,resizable=yes');\"/> <a title=\"Edit\" href=\"".$root_path."modules/supply_office/seg-inventory-ext-req.php".URL_APPEND."&userck=$userck&target=Edit&refno=".$row["refno"]."&from=list\"><img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_edit.gif\" border=\"0\" align=\"absmiddle\" /></a>
                            <a title=\"Delete\" href=\"#\">
                            <img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_delete.gif\" border=\"0\" align=\"absmiddle\" onclick=\"if (confirm('Delete this request?')) deleteRecord('".$row["refno"]."')\"/>
                            </a></td>";                                            
                        
                $rows .= "<tr class=\"$class\">
                              <td width=\"6%\">".$row["refno"]."</td>
                              <td width=\"7%\" align=\"center\">".strftime("%m-%d-%Y %I:%M%p",strtotime($row["request_date"]))."</td>
                              <td width=\"12%\">".$row["requesting_area"]."</td>                     
                              <td width=\"12%\">".$row["procurer"]."</td>
                              <td width=\"12%\">".$row["requestor"]."</td>
                              <td width=\"48%\">".$row["particulars"]."</td>".$btns."</tr>\n";                      
                $count++;                                                                            
            }    
        }
        else {
            print_r($result);
            $rows .= '        <tr><td colspan="7">'.$objrqst->sql.'</td></tr>';
        }

        if (!$rows) {
            $records_found = FALSE;
            $rows .= '        <tr><td colspan="7">No requests at this time</td></tr>';
        }    
        break;
        
    case 4:
        $result = $objrqst->getPostedPendingRequests($filters, $list_rows * $current_page, $list_rows, $str_areas);                   
        $rows = "";
        $last_page = 0;
        $count=0;
        $mycheck = 0; 
        //echo "0";
        //print_r($result);  
        if ($result) {
            $rows_found = $objrqst->FoundRows();
            
            if ($rows_found) {
                $last_page = floor($rows_found / $list_rows);
                $first_item = $current_page * $list_rows + 1;
                $last_item = ($current_page+1) * $list_rows;
                if ($last_item > $rows_found) $last_item = $rows_found;
                $nav_caption = "Showing ".number_format($first_item)."-".number_format($last_item)." out of ".number_format($rows_found)." record(s)";
            }

              while ($row = $result->FetchRow()) {       
                  $records_found = TRUE;
                  
                  $mycheck = $objrqst->checkIfNoPendingDetails($row["refno"]);
                 //echo "mycheck ".$mycheck;
                  if($mycheck == 1){
                      continue;
                  }            
                  
                  $btns = "<td align=\"right\" nowrap=\"nowrap\">   
                              <a title=\"Serve\" href=\"".$root_path."modules/supply_office/seg-issuance-test.php".URL_APPEND."&userck=$userck&target=Edit&refno=".$row["refno"]."&from=list&src=Inventory&ori_area=".$row["area_code_dest"]."&area_dest=".$row["area_code"]."\"><img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_edit.gif\" border=\"0\" align=\"absmiddle\" /></a>
                              <img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_delete.gif\" border=\"0\" align=\"absmiddle\" onclick=\"if (confirm('Delete this request?')) deleteRecord('".$row["refno"]."')\"/>
                              </a></td>";                                            
                          
                  $rows .= "<tr class=\"$class\">
                                <td width=\"6%\">".$row["refno"]."</td>
                                <td width=\"7%\" align=\"center\">".strftime("%m-%d-%Y %I:%M%p",strtotime($row["request_date"]))."</td>
                                <td width=\"12%\">".$row["requesting_area"]."</td>                     
                                <td width=\"12%\">".$row["requested_area"]."</td>
                                <td width=\"12%\">".$row["requestor"]."</td>
                                <td width=\"48%\">".$row["particulars"]."</td>".$btns."</tr>\n";                      
                  $count++;                                                                            
              }    
        }
        else {
            print_r($result);
            $rows .= '        <tr><td colspan="7">'.$objrqst->sql.'</td></tr>';
        }

        if (!$rows) {
            $records_found = FALSE;
            $rows .= '        <tr><td colspan="7">No requests at this time</td></tr>';
        }    
        break;
        
    case 5:
        $result = $objadjs->getPostedAdjustments($filters, $list_rows * $current_page, $list_rows, $str_areas);            
        $rows = "";
        $last_page = 0;
        $count=0;    
        if ($result) {
            $rows_found = $objadjs->FoundRows();
            if ($rows_found) {
                $last_page = floor($rows_found / $list_rows);
                $first_item = $current_page * $list_rows + 1;
                $last_item = ($current_page+1) * $list_rows;
                if ($last_item > $rows_found) $last_item = $rows_found;
                $nav_caption = "Showing ".number_format($first_item)."-".number_format($last_item)." out of ".number_format($rows_found)." record(s)";
            }
            
            while ($row = $result->FetchRow()) {       
                $records_found = TRUE;        
                
                $btns = "   <td align=\"right\" nowrap=\"nowrap\">
                            <a title=\"Edit\" href=\"".$root_path."modules/supply_office/seg-inventory-ext-req.php".URL_APPEND."&userck=$userck&target=Edit&refno=".$row["refno"]."&from=list\"><img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_edit.gif\" border=\"0\" align=\"absmiddle\" /></a>
                            <a title=\"Delete\" href=\"#\">
                            <img class=\"segSimulatedLink\" src=\"".$root_path."images/cashier_delete.gif\" border=\"0\" align=\"absmiddle\" onclick=\"if (confirm('Delete this request?')) deleteRecord('".$row["refno"]."')\"/>
                            </a></td>";                                            
                        
                $rows .= "<tr class=\"$class\">
                              <td width=\"6%\">".$row["refno"]."</td>
                              <td width=\"7%\" align=\"center\">".strftime("%m-%d-%Y %I:%M%p",strtotime($row["adjust_date"]))."</td>
                              <td width=\"12%\">".$row["adjusting_area"]."</td>                     
                              <td width=\"12%\">".$row["adjustor"]."</td>
                              <td width=\"*\">".$row["particulars"]."</td>".$btns."</tr>\n";                      
                $count++;                                                                            
            }    
        }
        else {
            print_r($result);
            $rows .= '        <tr><td colspan="7">'.$objadjs->sql.'</td></tr>';
        }

        if (!$rows) {
            $records_found = FALSE;
            $rows .= '        <tr><td colspan="7">No adjustments at this time</td></tr>';
        }    
        break;
}

ob_start();
?>
<form action="<?= $thisfile.URL_APPEND."&target=list&clear_ck_sid=".$clear_ck_sid.$src_link ?>" method="post" name="suchform" onSubmit="return validate()">
<div style="margin:5px;font-weight:bold;color:#660000"><?= $sWarning ?></div>
<div style="width:70%">
    <table width="100%" border="0" style="font-size: 12px; margin-top:5px" cellspacing="2" cellpadding="2">    
        <tbody>
            <tr>
                <td align="left" class="jedPanelHeader" ><strong>Search options</strong></td>
            </tr>
            <tr>
                <td nowrap="nowrap" align="left" class="jedPanel">
                    <table width="100%" border="0" cellpadding="2" cellspacing="0">
                        <tr>
                            <td width="50" align="right">
                                <input type="checkbox" id="chkspecific" name="chkspecific" onclick="selrecordOnChange(); keepFilters(0);" <?= ($_REQUEST['chkspecific'] ? 'checked' : '') ?>/>
                            </td>
                            <td width="5%" align="left" nowrap="nowrap">Specific Filter:</td>
                            <td>
<script language="javascript" type="text/javascript">
<!--
    function selrecordOnChange() {
        var optSelected = $('selrecord').options[$('selrecord').selectedIndex];
        var spans = document.getElementsByName('selrecordoptions');
        
        for (var i=0; i<spans.length; i++) {
            if (optSelected) {
                if (spans[i].getAttribute("segOption") == optSelected.value) {                
                    spans[i].style.display = $('chkspecific').checked ? "" : "none";
                }
                else
                    spans[i].style.display = "none";
            }
        }
        
//        disableNav()
    }
-->
</script>
                                <select class="jedInput" name="selrecord" id="selrecord" onchange="selrecordOnChange(); keepFilters(0);"/>
                                    <option value="name" <?= $_REQUEST["selrecord"]=="name" ? 'selected="selected"' : '' ?>>
                                    <?php 
                                        if($listtyp == 0) echo 'Received by';
                                        else if ($listtyp == 1) echo 'Requestor';
                                        else if ($listtyp == 3) echo 'Requestor';
                                        else echo 'Issuer';
                                    ?>
                                    </option>
                                    <option value="ref_no" <?= $_REQUEST["selrecord"]=="ref_no" ? 'selected="selected"' : '' ?>>Ref. No.</option>
                                    <option value="item_desc" <?= $_REQUEST["selrecord"]=="item_desc" ? 'selected="selected"' : '' ?>>Item Description</option>
                                </select>
                                <td>
                                <span name="selrecordoptions" segOption="name" <?= ($_REQUEST["selrecord"]=="name") && $_REQUEST['chkspecific'] ? '' : 'style="display:none"' ?>>
                                    <input class="jedInput" name="name" id="name" onblur="keepFilters(0);" type="text" size="30" value="<?= $_REQUEST['name'] ?>"/>
                                    <input type="hidden" name="name_old" value="<?= $_REQUEST['name'] ?>" />
                                </span>
                                <span name="selrecordoptions" segOption="ref_no" <?= ($_REQUEST["selrecord"]=="ref_no") && $_REQUEST['chkspecific'] ? '' : 'style="display:none"' ?>>
                                    <input class="jedInput" name="ref_no" id="ref_no" onblur="keepFilters(0);" type="text" size="30" value="<?= $_REQUEST['ref_no'] ?>"/>
                                </span>
                                <span name="selrecordoptions" segOption="item_desc" <?= ($_REQUEST["selrecord"]=="item_desc") && $_REQUEST['chkspecific'] ? '' : 'style="display:none"' ?>>
                                    <input class="jedInput" name="item_desc" id="item_desc" onblur="keepFilters(0);" type="text" size="30" value="<?= $_REQUEST['item_desc'] ?>"/>
                                </span></td>
                            </td>
                        </tr>                    
                        <tr>
                            <td width="5%" align="right"><input type="checkbox" id="chkdate" name="chkdate" <?= ($_REQUEST['chkdate'] ? 'checked' : '') ?> onclick="seldateOnChange();keepFilters(1);"/></td>
                            <td width="15%" nowrap="nowrap" align="left">Date <?= ($listtyp == 0 ? 'Received' : 'Requested') ?>:</td>
                            <td width="20%" align="left">
<script language="javascript" type="text/javascript">
<!--
    function seldateOnChange() {
        var filter = '';
    
        var optSelected = $('seldate').options[$('seldate').selectedIndex]
        var spans = document.getElementsByName('seldateoptions')
        for (var i=0; i<spans.length; i++) {
            if (optSelected) {
                if (spans[i].getAttribute("segOption") == optSelected.value) {
                    spans[i].style.display = $('chkdate').checked ? "" : "none";
                    
                    if (optSelected.value == "specificdate") 
                        filter = $(optSelected.value).value
                    else
                        filter = new Array($('between1').value, $('between2').value);
                }    
                else
                    spans[i].style.display = "none"
            }
        }        
        
        //disableNav()
    }
-->
</script>
                                <select class="jedInput" id="seldate" name="seldate" onchange="seldateOnChange(); keepFilters(1);">
                                    <option value="today" <?= $_REQUEST["seldate"]=="today" ? 'selected="selected"' : '' ?>>Today</option>
                                    <option value="thisweek" <?= $_REQUEST["seldate"]=="thisweek" ? 'selected="selected"' : '' ?>>This week</option>
                                    <option value="thismonth" <?= $_REQUEST["seldate"]=="thismonth" ? 'selected="selected"' : '' ?>>This month</option>
                                    <option value="specificdate" <?= $_REQUEST["seldate"]=="specificdate" ? 'selected="selected"' : '' ?>>Specific date</option>
                                    <option value="between" <?= $_REQUEST["seldate"]=="between" ? 'selected="selected"' : '' ?>>Between</option>
                                </select>
                                </td>
                                <td>
                                <span name="seldateoptions" segOption="specificdate" <?= ($_REQUEST["seldate"]=="specificdate") && $_REQUEST['chkdate'] ? '' : 'style="display:none"' ?>>
                                    <input onchange="keepFilters(1);" class="jedInput" name="specificdate" id="specificdate" type="text" size="8" value="<?= $_REQUEST['specificdate'] ?>"/>
                                    <img src="<?= $root_path ?>gui/img/common/default/show-calendar.gif" id="tg_specificdate" align="absmiddle" style="cursor:pointer"  />
                                    <script type="text/javascript">
                                        Calendar.setup ({
                                            inputField : "specificdate", ifFormat : "<?= $phpfd ?>", showsTime : false, button : "tg_specificdate", singleClick : true, step : 1
                                        });
                                    </script>
                                </span>
                                <span name="seldateoptions" segOption="between" <?= ($_REQUEST["seldate"]=="between") && $_REQUEST['chkdate'] ? '' : 'style="display:none"' ?>>
                                    <input onchange="keepFilters(1);" class="jedInput" name="between1" id="between1" type="text" size="8" value="<?= $_REQUEST['between1'] ?>"/>
                                    <img src="<?= $root_path ?>gui/img/common/default/show-calendar.gif" id="tg_between1" align="absmiddle" style="cursor:pointer;"  />
                                    <script type="text/javascript">
                                        Calendar.setup ({
                                            inputField : "between1", ifFormat : "<?= $phpfd ?>", showsTime : false, button : "tg_between1", singleClick : true, step : 1
                                        });
                                    </script>
                                    to
                                    <input onchange="keepFilters(1);" class="jedInput" name="between2" id="between2" type="text" size="8" value="<?= $_REQUEST['between2'] ?>"/>
                                    <img src="<?= $root_path ?>gui/img/common/default/show-calendar.gif" id="tg_between2" align="absmiddle" style="cursor:pointer"  />
                                    <script type="text/javascript">
                                        Calendar.setup ({
                                            inputField : "between2", ifFormat : "<?= $phpfd ?>", showsTime : false, button : "tg_between2", singleClick : true, step : 1
                                        });
                                    </script>
                                </span>
                            </td>
                        </tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr>
                            <td></td>
                            <td colspan="2">
                                <input type="submit" style="cursor:pointer" value="Search"  class="jedButton"/>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div style="width:95%">
    <table width="100%" class="segContentPaneHeader" style="margin-top:10px">
    <tr><td>
        <h1>
            Search result:
<?php
    echo $search_title;    
     ?></h1></td>        
    </tr>
    </table>
    <div class="segContentPane">
        <table id="" class="jedList" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr class="nav">
                    <th colspan="9">
                        <div id="pageFirst" class="<?= ($current_page > 0) ? 'segSimulatedLink' : 'segDisabledLink' ?>" style="float:left" onclick="<?= ($current_page > 0) ? 'jumpToPage(FIRST_PAGE)' : '' ?>">
                            <img title="First" src="<?= $root_path ?>images/start.gif" border="0" align="absmiddle"/>
                            <span title="First">First</span>
                        </div>
                        <div id="pagePrev" class="<?= ($current_page > 0) ? 'segSimulatedLink' : 'segDisabledLink' ?>" style="float:left" onclick="<?= ($current_page > 0) ? 'jumpToPage(PREV_PAGE)' : '' ?>">
                            <img title="Previous" src="<?= $root_path ?>images/previous.gif" border="0" align="absmiddle"/>
                            <span title="Previous">Previous</span>
                        </div>
                        <div id="pageShow" style="float:left; margin-left:10px">
                            <span><?= $nav_caption ?></span>
                        </div>
                        <div id="pageLast" class="<?= ($current_page < $last_page) ? 'segSimulatedLink' : 'segDisabledLink' ?>" style="float:right" onclick="<?= ($current_page < $last_page) ? 'jumpToPage(LAST_PAGE)' : '' ?>">
                            <span title="Last">Last</span>
                            <img title="Last" src="<?= $root_path ?>images/end.gif" border="0" align="absmiddle"/>
                        </div>
                        <div id="pageNext" class="<?= ($current_page < $last_page) ? 'segSimulatedLink' : 'segDisabledLink' ?>" style="float:right" onclick="<?= ($current_page < $last_page) ? 'jumpToPage(NEXT_PAGE)' : '' ?>">
                            <span title="Next">Next</span>
                            <img title="Next" src="<?= $root_path ?>images/next.gif" border="0" align="absmiddle"/>
                        </div>
                    </th>
                </tr>
                <tr>    
<?php switch ($listtyp) { 
        case 0: ?>            
                    <th width="6%" align="center">Ref No.</th>
                    <th width="7%" align="center">Date Rcvd</th>
                    <th width="14%" align="center">Remarks</th>
                    <th width="12%" align="center">Rcvd By</th>
                    <th width="48%" align="center">Particulars</th>
                    <th width="10%" align="center">Amount</th>
                    <th width="3%">&nbsp;</th>
<?php      break; 
        case 1:  ?>
                    <th width="6%" align="center">Ref No.</th>
                    <th width="7%" align="center">Date</th>
                    <th width="12%" align="center">Requesting</th>
                    <th width="12%" align="center">Requested</th>
                    <th width="12%" align="center">Requestor</th>
                    <th width="48%" align="center">Particulars</th>
                    <th width="3%">&nbsp;</th>   
<?php      break; 
        case 2:  ?> 
                    <th width="6%" align="center">Ref No.</th>
                    <th width="7%" align="center">Date</th>
                    <th width="12%" align="center">Issued by</th>
                    <th width="12%" align="center">Issued to</th>
                    <th width="12%" align="center">Issuer</th>
                    <th width="48%" align="center">Particulars</th>
                    <th width="3%">&nbsp;</th>                                  
<?php      break;
        case 3:  ?> 
                    <th width="6%" align="center">Ref No.</th>
                    <th width="7%" align="center">Date</th>
                    <th width="12%" align="center">Requesting</th>
                    <th width="12%" align="center">Procurer</th>
                    <th width="12%" align="center">Requestor</th>
                    <th width="48%" align="center">Particulars</th>
                    <th width="3%">&nbsp;</th>                                    
<?php      break;
          case 4:  ?>
                    <th width="6%" align="center">Ref No.</th>
                    <th width="7%" align="center">Date</th>
                    <th width="12%" align="center">Requesting</th>
                    <th width="12%" align="center">Requested</th>
                    <th width="12%" align="center">Requestor</th>
                    <th width="48%" align="center">Particulars</th>
                    <th width="3%">&nbsp;</th>   
<?php      break; 
          case 5:  ?>
                    <th width="6%" align="center">Ref No.</th>
                    <th width="7%" align="center">Date</th>
                    <th width="12%" align="center">Adjusting</th>
                    <th width="12%" align="center">Requestor</th>
                    <th width="*" align="center">Particulars</th>
                    <th width="3%">&nbsp;</th>   
<?php      break; 
         } ?>                  
                </tr>
            </thead>
            <tbody>
<?= $rows ?>
            </tbody>
        </table>
        <br />
    </div>
</div>

<?php

# Workaround to force display of results  form
$bShowThisForm = TRUE;

# If smarty object is not available create one
if(!isset($smarty)){
    /**
 * LOAD Smarty
 * param 2 = FALSE = dont initialize
 * param 3 = FALSE = show no copyright
 * param 4 = FALSE = load no javascript code
 */
    include_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('common',FALSE,FALSE,FALSE);
    
    # Set a flag to display this page as standalone
    $bShowThisForm=TRUE;
}

?>

<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="userck" value="<?php echo $userck ?>">
<input type="hidden" name="cat" value="<?php echo $cat?>">
<input type="hidden" name="userck" value="<?php echo $userck?>">  
<input type="hidden" name="dstamp" value="<?php echo  str_replace("_",".",date(Y_m_d))?>">
<input type="hidden" name="tstamp" value="<?php echo  str_replace("_",".",date(H_i))?>">
<input type="hidden" name="lockflag" value="<?php echo  $lockflag?>">
<!--added by bryan-->
<input type="hidden" id="from" name="from" value="<?= $_REQUEST['from'] ?>" />

<input type="hidden" id="delete" name="delete" value="" />
<input type="hidden" id="page" name="page" value="<?= $current_page ?>" />
<input type="hidden" id="lastpage" name="lastpage"  value="<?= $last_page ?>" />
<input type="hidden" id="jump" name="jump">
<input type="hidden" id="applied" name="applied" value="1"> 
<input type="hidden" id="root_path" name="root_path" value="<?php echo $root_path ?>" />
<input type="hidden" id="list" name="list" value="<?= $_GET["list"] ?>">
<!--<input type="hidden" id="fill_up" name="fill_up" value="">-->
<!--<div style="display:none" id="cases_selected">
    <table id="cases">
        <tbody>
        </tbody>
    </table>
</div>
<div style="display:none" id="cases_list"></div>-->
</form>
<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign the form template to mainframe

 $smarty->assign('sMainFrameBlockData',$sTemp);

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');
?>
