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
define('LANG_FILE','products.php');
define('NO_2LEVEL_CHK',1);
$local_user='ck_prod_db_user';
require_once($root_path.'include/inc_front_chain_lang.php');

#$breakfile='apotheke.php'.URL_APPEND;
$breakfile=$root_path.'main/startframe.php'.URL_APPEND;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 # Create a helper smarty object without reinitializing the GUI
 $smarty2 = new smarty_care('common', FALSE);

 # Title in the title bar
 $smarty->assign('sToolbarTitle',"Inventory::Supplies");

 # href for the back button
// $smarty->assign('pbBack',$returnfile);

 # href for the help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','Supply Office::Supplies')");

 # href for the close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',"Supply Office::Supplies");

 # Prepare the submenu icons       

 $aSubMenuIcon=array(createComIcon($root_path,'comments.gif','0'),
                     createComIcon($root_path,'memo_archives.gif','0'),   
                     createComIcon($root_path,'update4.gif','0'),
                     createComIcon($root_path,'thumbs_up.gif','0'),
                     createComIcon($root_path,'articles.gif','0'),
                     createComIcon($root_path,'hfolder1.gif','0'),
                     createComIcon($root_path,'indexbox2.gif','0'),
                     //createComIcon($root_path,'quote.gif','0'),
                     //createComIcon($root_path,'task_tree1.gif','0'),
                     createComIcon($root_path,'manager.gif','0'),
                     createComIcon($root_path,'chart.gif','0'),
                     createComIcon($root_path,'calendar.gif','0'),
                     createComIcon($root_path,'archives.gif','0'),
                     createComIcon($root_path,'book_error.png','0'),
                     #createComIcon($root_path,'indexbox2.gif','0'),
                     );
                     
 # Prepare the submenu item descriptions

 $aSubMenuText=array("Post a request.",
                     "History of requests made by your department.",
                     "Process issuance for requests made by your department.",
                     "Acknowledge or accept issuances to your department.",
                     "History of your issuances.", 
                     "Process deliveries to your department.",
                     "History of deliveries processed.",
                     //"Post External Request.",
                     //"History of External Requests.",
                     "Adjust supplies in the inventory.",
                     "Show inventory reports.",
                     "View Stock Card.",
                     "Manage inventory and inventory information.",
                     "List pending requests to your department.",
                     #"List Posted Adjustments in your department.",
                     );                 

 # Prepare the submenu item links indexed by their template tags

 $aSubMenuItem=array('LDSegSupplyRequest' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=requestnew">Requisition</a>',
                     'LDRequestsHistory' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=managereq">Requisition History</a>',
                     'LDSegSupplyIssuance' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=issuancenew">Process Issuance</a>',
                     'LDSegSupplyAcknowledge' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=issuanceack&from=">Acknowledge Issuance</a>',  
                     'LDIssuanceHistory' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=manageiss">Issuance History</a>',    
                     'LDSegSupplyDelivery' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=newdelivery"">Process Delivery</a>',
                     'LDSegSupplyDeliveries' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=deliveries"">Posted Deliveries</a>',
                     //'LDSegSupplyExtRequest' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=newextreq">Post External Request</a>',
                     //'LDSegSupplyExtRequestsHistory' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=extreqs">Posted External Requests</a>',             
                     'LDSegSupplyAdjustment' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=adjustment"">Adjustment</a>',
                     'LDSegInvReport' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=reports"">Generate Reports</a>',
                     'LDSegStockCard' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=stockcard">Stock Card</a>', 
                     'LDSegInvProdDBank' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=prodbank"">Inventory Databank</a>', 
                     'LDInvPostedRequests' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=pending_requests"">Serve Pending Requests</a>',
                     #'LDSegSupplyAdjustmentHistory' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=hisadjustments">Adjustment History</a>',
                     );
/*
$aSubMenuItem=array('LDSegSupplyIssuance' => '<a href="'.$root_path.'modules/supply_office/seg-issuance-test.php'. URL_APPEND."&userck=$userck".'&target=issuancenew&from=">Create new issuance</a>',
                    'LDSegSupplyAcknowledge' => '<a href="'.$root_path.'modules/supply_office/seg-issuance-acknowledge.php'. URL_APPEND."&userck=$userck".'&target=issuanceack&from=">Acknowledge issuance</a>',
                    'LDSegSupplyRequest' => '<a href="'.$root_path.'modules/supply_office/seg-check-user.php'. URL_APPEND."&userck=$userck".'&target=requestnew"">Request Supplies</a>' 
                                        );
*/                                        
# Create the submenu rows

$iRunner = 0;

while(list($x,$v)=each($aSubMenuItem)){
    $sTemp='';
    ob_start();
    if($cfg['icons'] != 'no_icon') $smarty2->assign('sIconImg','<img '.$aSubMenuIcon[$iRunner].'>');
    $smarty2->assign('sSubMenuItem',$v);
    $smarty2->assign('sSubMenuText',$aSubMenuText[$iRunner]);
    $smarty2->display('common/seg_submenu_row.tpl');
    $sTemp = ob_get_contents();
     ob_end_clean();
    $iRunner++;
    $smarty->assign($x,$sTemp);
}

# Assign the submenu items table to the subframe

# Assign the subframe to the mainframe center block
$smarty->assign('sMainBlockIncludeFile','supply_office/menu_supply.tpl');

  /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');
?>