<?php
ini_set("memory_limit","-1");
ini_set("max_execution_time",0);
set_time_limit(0);
require './roots.php';
require $root_path.'include/inc_environment_global.php';
require_once $root_path.'include/care_api_classes/class_hospital_admin.php';

//CALLING LIBRARY for PDF OR EXCEL REPORTS

include_once($root_path.'include/phpjasperxml-master/tcpdf/tcpdf.php');
include_once($root_path.'include/phpjasperxml-master/PHPJasperXML.inc.php');
include ($root_path.'phpjasperxml-master/sample/setting.php');
include $root_path.'include/inc_init_main.php';
/**
 * SegHIS - Hospital Information System (DMC Deployment)
 * Enhanced by Segworks Technologies Corporation
 */
//ADDED BY: ALLAN III CONDIMAN IF NOT CHOOSE PDF OR EXCELL RADIO BUTTON
if ($_GET['pdf'] == null && $_GET['excel'] == null){
    echo '<script>alert("Please Choose PDF OR Excel Reports"); 
        window.close();</script>';
}

global $db;
$encode = $_GET['encoder'];
$id = $_GET['type'];
$date_start = date("Ymd", strtotime($_GET['datestart']));
$date_end = date("Ymd", strtotime($_GET['dateend']));
$start_time = $_GET['timestart'];
$where = array();
$where = array();
$having = array();
$where2 = array();
$having2 = array();
$total_cash = 0;
$total_others = 0;
$total_rd = 0;
$total_ld = 0;
$total_csr = 0;
$total_check = 0;
$total_ph = 0;
$total_pf = 0;
$hosp_system = 0;
$tcc = 0;
$total_refund = 0;
//Hospital db
$sql = "SELECT hosp_name from seg_hospital_info";
$hosp_name = $db->GetOne($sql);

//account type
$sql_acc_type = "SELECT formal_name FROM seg_pay_accounts WHERE id ='$id'";
$acc_type = $db->GetOne($sql_acc_type);
$acc_type_name = "DAILY COLLECTION REPORT (" . $acc_type . ")";
//Name of Encoder
if ($encode) {
    $sql = "SELECT name FROM care_users WHERE login_id ='$encode'";
    $encodes = $db->GetOne($sql);
}

if (!$start_time)
    $start_time = "000000";

$end_time = $_GET['timeend'];
if (!$end_time)
    $end_time = "23000";
$end_time = $end_time . "0";

if ($date_start) {
    $date_start;
} else {
    $date_start = date("Ymd");
}

if ($date_end) {
    $date_end;
} else {
    $date_end = $date_start;
}

$dates = strtoupper(date("M j, Y h:ia", strtotime($date_start . " " . $start_time))) . " - " .
    strtoupper(date("M j, Y h:ia", strtotime($date_end . " " . $end_time)));

$query = "SELECT pay.or_date, pay.or_no, pay.or_name, IF( pd.`ref_source` = 'other',
                'MISC', IF( pd.ref_source = 'PH', (SELECT IF( prod_class = 'S','CSR',
                pd.ref_source) FROM care_pharma_products_main WHERE bestellnum = 
                pd.service_code), pd.ref_source)) AS ref_source, CASE pd.`ref_source` 
                WHEN 'PH' THEN (SELECT artikelname FROM care_pharma_products_main cpm
                WHERE cpm.bestellnum = pd.service_code) WHEN 'MISC' THEN (SELECT sos.name
                FROM seg_other_services sos WHERE sos.alt_service_code = pd.service_code)
                WHEN 'LD' THEN (SELECT sls.name FROM seg_lab_services sls 
                WHERE sls.service_code = pd.service_code) WHEN 'RD' THEN (SELECT srs.name
                FROM seg_radio_services srs WHERE srs.service_code = pd.service_code)
                WHEN 'OTHER' THEN (SELECT sos.name FROM seg_other_services sos
                WHERE sos.service_code = SUBSTRING(pd.service_code, 1, 8)) WHEN 'PF'
                THEN (SELECT CONCAT( 'DR. ', cp.name_last, ', ', cp.name_first) FROM
                care_personell cpl LEFT JOIN care_person cp ON cp.pid = cpl.pid
                WHERE cpl.short_id = pd.service_code) ELSE 'other' END AS service_code,
                round((pd.amount_due - (pay.discount_tendered/(SELECT COUNT(*) FROM 
                seg_pay_request WHERE or_no = pay.or_no))),2) amount, pay.create_id,
                pay.create_dt, (pay.cancel_date IS NOT NULL) is_cancelled, pay.cancel_date,
                pay.cancelled_by, spc.check_no FROM seg_pay_request pd INNER JOIN 
                seg_pay pay ON pd.or_no = pay.or_no LEFT JOIN seg_pay_checks spc
                ON spc.or_no = pay.or_no ";


$where[] = "pay.or_date BETWEEN DATE_FORMAT(" . $db->qstr($date_start . $start_time) . ", '%Y-%m-%d %T') AND DATE_FORMAT(" .
    $db->qstr($date_end . $end_time) . ", '%Y-%m-%d %T' )";

$where[] = "cancel_date IS NULL ";

if ($_GET['orfrom'])
    $where[] = "CAST(pay.or_no AS UNSIGNED) >= " . $db->qstr($_GET['orfrom']);


if ($_GET['orto'])
    $where[] = "CAST(pay.or_no AS UNSIGNED) <= " . $db->qstr($_GET['orto']);

if ($encode) {
    $where[] = "create_id=" . $db->qstr(utf8_decode($encode));
}

if ($where) {
    $query .= "WHERE (" . implode(") AND (", $where) . ")\n";
}

if ($having) {
    $query .= "HAVING (" . implode(") AND (", $having) . ")\n";
}
$query .= "ORDER BY pay.or_date ASC";

$query2 = "SELECT
        				  SUM(refund_amount) AS total_refund
        				FROM
        				  seg_credit_memos ";
$where2[] = "issue_date BETWEEN DATE_FORMAT(".$db->qstr($date_start.$start_time).", '%Y-%m-%d %T') AND DATE_FORMAT(".
    $db->qstr($date_end.$end_time).", '%Y-%m-%d %T' )";
$where2[] = "status = 0";

if ($_GET['orfrom'])
    $where2[] = "CAST(pay.or_no AS UNSIGNED) >= ".$db->qstr($_GET['orfrom']);

if ($_GET['orto'])
    $where2[] = "CAST(pay.or_no AS UNSIGNED) <= ".$db->qstr($_GET['orto']);

if ($encode)
{
    $where2[]="personnel=".$db->qstr($encode);
}

if ($where2)
{
    $query2 .= "WHERE (" . implode(") AND (",$where2) . ")\n";
}

$result2 = $db->query($query2);

foreach ($result2 as $rows){
    if($rows)
        $total_refund = $rows['total_refund'];

}

$result = $db->Execute($query);
if ($_GET['pdf']) {
    include 'pdf.php';
}else {
    $PHPJasperXML = new PHPJasperXML("en", "XLS");
}
$i = 0;
$no_result = array();
$datas = array();

if ($result) {
    if ($result->RecordCount()) {
        while ($row = $result->FetchRow()) {
            $orno = $row['or_no'];
            if (!$datas[$orno]) {
                $orno = $row['or_no'];
                $check = $row['check_no'];
                if (!$row['check_no']) {
                    switch (strtolower($row['ref_source'])) {
                        case 'ph':
                            $total_ph += $row['amount'];
                            break;
                        case 'csr':
                            $total_csr += $row['amount'];
                            break;
                        case 'ld':
                            $total_ld += $row['amount'];
                            break;
                        case 'rd':
                            $total_rd += $row['amount'];
                            break;
                        case 'pf':
                            $total_pf += $row['amount'];
                            break;
                        default:
                            if (strtoupper($row['service_code']) == "HOSPITAL INFORMATION SYSTEM" && $row['ref_source'] == "MISC") {
                                $hosp_system += $row['amount'];
                            } else {
                                $total_others += $row['amount'];
                            }
                            break;
                    }
                    $total_cash += $row['amount'];
                } else {
                    $total_check += $row['amount'];
                }
            }
            
            $total_cash2 = $tcc + $total_check;
            $remaining = $total_cash - $total_refund;
            $total_coll = $total_cash + $total_check;
            $total_cash_coll = $remaining + $total_refund;
            $final_total_coll = $total_coll - $total_refund;
        }
    } else {
        $no_result[0] = array(
            'or_no' => "No",
            'or_date' => "Payemnt Found...",
        );
    }
}
$name_of_encoder = $encodes == null ? "ALL ENCODERS" : $encodes;
$PHPJasperXML->arrayParameter =
    array("Hospital" => $hosp_name,
        "Acc_type" => $acc_type_name,
        "Dates" => $dates,
        "Encoder" => $name_of_encoder,
        "Total" => " ".number_format($total_coll, 2),
        "Pharma" => number_format($total_ph, 2),
        "CSR" => number_format($total_csr, 2),
        "Lab" => number_format($total_ld, 2),
        "Rad" => number_format($total_rd, 2),
        "OI" => number_format($total_others, 2),
        "PROF" => number_format($total_pf, 2),
        "OI" => number_format($total_others, 2),
        "HIS" => number_format($hosp_system, 2),
        "TCC" => number_format($total_cash_coll, 2),
        "Tcr" => number_format($remaining, 2),
        "TChC"=>number_format($total_check,2),
        "LR"=>number_format($total_refund,2),
        "TC"=>number_format($final_total_coll,2),
    );

if ($_GET['excel']){
    $PHPJasperXML->load_xml_file($root_path."reports/cashier.jrxml");
    $PHPJasperXML->arraysqltable = $no_result;
    $PHPJasperXML->sql = $query;
    $PHPJasperXML->transferDBtoArray($dbhost, $dbusername, $dbpassword, $dbname);
    $PHPJasperXML->outpage("I",$name_of_encoder. " - ". $acc_type.".xls");
}









