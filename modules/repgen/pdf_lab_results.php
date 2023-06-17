<?php
//created by EJ 09/13/2014
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
require($root_path . '/modules/repgen/repgen.inc.php');
require_once($root_path . 'include/care_api_classes/class_hospital_admin.php');
require_once($root_path . 'include/care_api_classes/class_lab_results.php');
require_once($root_path . 'include/care_api_classes/class_department.php');
require_once($root_path . 'include/care_api_classes/class_ward.php');


class RepGen_LabResults extends RepGen
{
    var $pid, $refno, $group_id, $gender, $done, $service_code;
    var $colored = TRUE;

    function RepGen_LabResults()
    {

        $this->pid = $_GET["pid"];
        $this->refno = $_GET["refno"];
        if (isset($_GET["group_id"]) && $_GET["group_id"] != '')
            $this->group_id = $_GET["group_id"];
        else
            $this->group_id = '0';
        if (isset($_GET["service_code"]) && $_GET["service_code"] != '')
            $this->service_code = $_GET["service_code"];
        else
            $this->service_code = '0';

        $lab_results = new Lab_Results();

        if ($group_id)
            $this->RepGen("LAB RESULT FOR " + $lab_results->get_group_name($this->group_id));
        if ($service_code)
            $this->RepGen("LAB RESULT FOR " + $lab_results->get_service_name($this->service_code));

        $this->PageOrientation = "P";
        $this->FPDF('p', 'mm', 'letter');

        if ($this->colored) $this->SetDrawColor(0xDD);
    }

    function Header()
    {

        $lab_results = new Lab_Results();
        $dept_obj = new Department;
        $ward_obj = new Ward;

        global $root_path, $db;
        $objInfo = new Hospital_Admin();
        if ($row = $objInfo->getAllHospitalInfo()) {
            $row['hosp_agency'] = strtoupper($row['hosp_agency']);
            $row['hosp_name']   = strtoupper($row['hosp_name']);
        } else {
            $row['hosp_country'] = "Republic of the Philippines";
            $row['hosp_agency']  = "PROVINCE OF CAMIGUIN";
            $row['hosp_name']    = "CAMIGUIN GENERAL HOSPITAL";
            $row['hosp_addr1']   = "Mambajao, Camiguin Province";
        }

        $this->Image('../../gui/img/logos/cmhi_logo.jpg', 40, 6, 15, 15);

        $this->SetXY(0, 8);
        $this->SetFont("Arial", "B", 12);
        $this->Cell(0, 4, $row['hosp_name'], 0, 1, "C");

        $this->SetFont("Arial", "", 10);
        $this->Cell(0, 4, "(Affiliated with Our Lady of Mercy Diagnostic Center, Davao City)", 0, 1, "C");
        $this->Cell(0, 2, "2081 NATIONAL HIGHWAY, SALVACION, PANABO CITY", 0, 1, "C");

        $this->Ln(6);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 3, strtoupper($lab_results->get_group_name($_GET['group_id'])), '', 0, 'C');

        $patient = $lab_results->get_patient_data($this->refno, $this->group_id);
        if ($patient != NULL)
            extract($patient);
        else {
            $sql = "SELECT * from seg_walkin WHERE pid='$this->pid'";
            $rs = $db->Execute($sql);
            if ($rs && $pt = $rs->FetchRow()) {
                extract($pt);
            }
            $ordername = mb_strtoupper($name_last) . ", " . mb_strtoupper($name_first) . " " . mb_strtoupper($name_middle) . ".";
        }
        $sql = "SELECT service_date FROM seg_lab_resultdata WHERE refno='$this->refno' AND group_id='$this->group_id' AND (ISNULL(`status`) OR `status`!='deleted');";
        $result = $lab_results->exec_query($sql);
        if ($result) {
            if ($resdata = $result->FetchRow())
                $date =  date("m/d/Y", strtotime(substr($resdata["service_date"], 0, -9)));
        }

        $this->SetDrawColor(0, 0, 0);

        $this->SetXY(10, 30);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(14, 3, "NAME: ", 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 3, strtoupper($ordername), 'B', 0, 'C');

        $format_age = explode(' ', $age);
        if ($format_age[1] == 'years') {
            $format_age[1] = 'Y/O';
        }
        if ($format_age[2] == 'and') {
            $format_age[2] = '';
        }
        if ($format_age[4] == 'months') {
            $format_age[4] = 'MOS';
        }
        $format_age = implode(' ', $format_age);

        $this->SetXY(85, 30);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(12, 3, 'AGE: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(40, 3, strtoupper($format_age), 'B', 1, 'C');

        $this->SetXY(140, 30);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(12, 3, 'SEX: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(5, 3, strtoupper($sex), 'B', 1, 'C');

        $this->SetXY(160, 30);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(12, 3, 'DATE: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(25, 3, strtoupper($date), 'B', 1, 'C');

        $sql = "SELECT CONCAT(IF(ISNULL(name_first), '', CONCAT(name_first, ' ')), IF(ISNULL(name_middle), '', CONCAT(name_middle, ' ')), IF(ISNULL(name_last), '', name_last)) as name FROM care_person LEFT JOIN care_personell ON care_personell.pid=care_person.pid WHERE nr='" . $request_doctor . "'";
        $result = $lab_results->exec_query($sql);
        if ($result != NULL && $resdata = $result->FetchRow()) {
            $physician = $resdata["name"];
        }

        $this->SetXY(10, 35);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(22, 3, 'PHYSICIAN: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(72, 3, strtoupper($physician), 'B', 0, 'C');

        $this->SetXY(105, 35);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(25, 3, 'WARD/ROOM #: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(67, 3, (strtoupper($ward_name ? $ward_name . ' Room ' . $current_room_nr : '')), 'B', 1, 'C');

        $sql = "SELECT sc.comp_name as company_name
        FROM seg_company_allotment AS sca 
        LEFT JOIN seg_company AS sc 
        ON sc.comp_id = sca.comp_id 
        LEFT JOIN care_encounter AS ce 
        ON sca.`encounter_nr` = ce.`encounter_nr` 
        WHERE ce.`pid` = '$this->pid' ";
        $result = $lab_results->exec_query($sql);

        if ($result) {
            if ($company = $result->FetchRow())
                $company_name =  $company["company_name"];
        }

        $this->SetXY(10, 40);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 3, 'C/O: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(50, 3, strtoupper($company_name), 'B', 1, 'C');

        if ($age)
            $age = $age;
        else
            $age = "";

        $base_age = explode(' ', $age);
        $norm_type = "norm_type = 'none'";

        if ($base_age[1] == "months" || $base_age[1] == 'month' || $base_age[1] == "day" || $base_age[1] == "days") {
            $this->gender = "(norm_type = 'newborn' OR norm_type = 'none')";
        } else if ($base_age[1] == "years" || $base_age[1] == 'year') {
            if ($base_age[0] <= 3) {
                $this->gender = "(norm_type = 'infant'  OR norm_type = 'none')";
            } else if ($base_age[0] <= 12 && $base_age[0] >= 4) {
                $this->gender = "(norm_type = 'children' OR norm_type = 'none')";
            } else if ($base_age[0] > 12) {
                if (strtoupper($sex) == "M") {
                    $this->gender = "(norm_type = 'male' OR norm_type = 'none')";
                } else {
                    $this->gender = "(norm_type = 'female' OR norm_type = 'none')";
                }
            }
        }


        $this->LabResult_body();
    }

    function LabResult_body()
    {
        global $db;

        $lab_results = new Lab_Results();

        if ($this->service_code) {
            $sql = "SELECT p.*, r.result_value, r.unit, s.name as group_name
        FROM seg_lab_result_params AS p
        LEFT JOIN seg_lab_services AS s ON s.service_code = p.service_code
        LEFT JOIN seg_lab_result AS r ON r.param_id = p.param_id AND r.refno='$this->refno' AND (ISNULL(r.status) OR r.status!='deleted')
        WHERE $this->gender  AND (p.status <> 'deleted' OR p.`status` IS NULL) AND s.service_code='$this->service_code' ORDER BY p.order_nr ASC";
        }
        if ($this->group_id) {
            $sql = "SELECT p.*, r.result_value, r.unit, pg.name as group_name, gp.order_nr as order2, IF(ISNULL(d.refno), 0, 1) AS enabled
        FROM seg_lab_result_groupparams as gp
        LEFT JOIN seg_lab_result_params as p ON p.service_code = gp.service_code
        LEFT JOIN seg_lab_result as r ON p.param_id = r.param_id AND r.refno='$this->refno' AND (ISNULL(r.status) OR r.status!='deleted')
        LEFT JOIN seg_lab_result_paramgroups as pg ON pg.param_group_id = p.param_group_id
        LEFT JOIN seg_lab_servdetails AS d ON d.service_code=p.service_code AND d.refno='$this->refno'
        WHERE gp.group_id=$this->group_id AND $this->gender AND (ISNULL(p.status) OR p.status NOT IN ('deleted'))
        AND (ISNULL(gp.status) OR gp.status!='deleted') AND (r.result_value IS NOT NULL AND r.result_value != '') 
        UNION SELECT p.*, r.result_value, r.unit, pg.name as group_name, gp.order_nr as order2, IF(ISNULL(d.refno), 0, 1) AS enabled
        FROM seg_lab_result_groupparams as gp
        LEFT JOIN seg_lab_result_group as g ON g.service_code = gp.service_code
        LEFT JOIN seg_lab_result_params as p ON p.service_code = g.service_code_child
        LEFT JOIN seg_lab_result as r ON p.param_id = r.param_id AND r.refno='$this->refno' AND (ISNULL(r.status) OR r.status!='deleted')
        LEFT JOIN seg_lab_result_paramgroups as pg ON pg.param_group_id = p.param_group_id
        LEFT JOIN seg_lab_servdetails AS d ON (d.service_code=g.service_code OR d.service_code=p.service_code) AND d.refno='$this->refno'
        WHERE gp.group_id=$this->group_id AND $this->gender AND (ISNULL(p.status) OR p.status NOT IN ('deleted'))
        AND (ISNULL(gp.status) OR gp.status!='deleted') AND (r.result_value IS NOT NULL AND r.result_value != '') 
        ORDER BY order_nr, order2";
        }

        $result = $lab_results->exec_query($sql);

        /*if ($result) {
    $total_results_count = $result->RecordCount();
    }
    else {
    $this->SetFont('Arial','',20);
    $this->SetXY(90,50);
    $this->Cell(25,3,'(NO DATA)', 0,0,'L');   
    }*/

        if ($result) {
            $numres = $result->RecordCount();
            if ($this->group_id)
                $sql = "SELECT 
                  COUNT(result_value) as numres
                FROM
                  seg_lab_result 
                WHERE refno = $this->refno 
                  AND result_value != '' 
                  AND result_value IS NOT NULL ";

            $rs = $lab_results->exec_query($sql);

            if ($rs != NULL && $val = $rs->FetchRow()) $numres = $val["numres"];

            //set result headers
            $this->SetFont('Arial', 'B', 12);

            $this->SetXY(10, 50);
            $this->Cell(25, 3, 'EXAMINATIONS', 0, 0, 'L');
            $this->SetXY(95.5, 50);
            $this->Cell(25, 3, 'RESULTS', 0, 0, 'L');
            $this->SetXY(155, 50);
            $this->Cell(27, 3, 'NORMAL VALUES', 0, 0, 'L');

            /* if ($total_results_count >= 15) {
    $this->SetXY(110,45);
    $this->Cell(25,3,'EXAMINATIONS', 0,0,'L');
    $this->SetXY(137.5,45);
    $this->Cell(25,3,'RESULTS', 0,0,'L');
    $this->SetXY(160,45);
    $this->Cell(25,3,'NORMAL VALUES', 0,0,'L');
    }*/ //commented by mai 09-18-2014

            //declare first column axis
            $x = 10;
            $y = 60;

            //display the results
            while ($result != NULL && $value = $result->FetchRow()) {

                //assign values
                $name = $value["name"];
                $group_name = $value["group_name"];
                $result_value = $value["result_value"];
                $SI_lo_normal = $value["SI_lo_normal"];
                $SI_hi_normal = $value["SI_hi_normal"];
                $SI_unit = $value["SI_unit"];

                $this->SetFont('Arial', '', 12);

                //if no groups
                if (!$group_name) {
                    $this->SetXY($x, $y);
                    $this->Cell(1, 1, $name, '', 1, 'L');
                    $this->SetXY($x + 90, $y);
                    $this->Cell(10, 3, $result_value, 'B', 1, 'C');
                    $this->SetXY($x + 150, $y + 1);
                    $this->Cell(1, 1, $SI_lo_normal . " - " . $SI_hi_normal . "" . $SI_unit, '', 1, 'L');
                }

                //if has groups
                else {
                    $this->SetXY($x, $y);
                    $group_name_current = $group_name;
                    if ($group_name_old == $group_name_current) {
                        $this->Cell(1, 1, '', '', 1, 'L');
                        $y = $y - 5;
                    } else {
                        $this->Cell(1, 1, $group_name_current, '', 1, 'L');
                        $group_name_old = $group_name_current;
                    }
                    $this->SetXY($x + 10, $y + 5);
                    $this->Cell(1, 1, $name, '', 1, 'L');
                    $this->SetXY($x + 90, $y + 3);
                    $this->Cell(10, 3, $result_value, 'B', 1, 'C');
                    $this->SetXY($x + 150, $y + 4);
                    $this->Cell(1, 1, $SI_lo_normal . " - " . $SI_hi_normal . "" . $SI_unit, '', 1, 'L');
                    $y = $y + 5;
                }

                //collect result y axis
                $y = $y + 5;

                //next column
                /*if ($y >= 130) {

        $x = 100;
        $y = 50;
        
        //if no groups
        if (!$group_name) {
        $this->SetXY($x,$y);
        $this->Cell(1,1,$name, '',1,'L'); 
        $this->SetXY($x+40,$y);
        $this->Cell(10,3,$result_value, 'B',1,'C');
        $this->SetXY($x+60,$y+1);
        $this->Cell(1,1,$SI_lo_normal."   ".$SI_hi_normal."".$SI_unit, '',1,'L');   
        }

        //if has groups
        else {
        $this->SetXY($x,$y);
        $group_name_current = $group_name;
        if ($group_name_old == $group_name_current ) {
            $this->Cell(1,1,'', '',1,'L');
        }
        else {
            $this->Cell(1,1,$group_name_current, '',1,'L');
            $group_name_old = $group_name_current;
        }
        $this->SetXY($x+10,$y+5);
        $this->Cell(1,1,$name, '',1,'L'); 
        $this->SetXY($x+40,$y+3);
        $this->Cell(10,3,$result_value, 'B',1,'C');
        $this->SetXY($x+60,$y+4);
        $this->Cell(1,1,$SI_lo_normal."   ".$SI_hi_normal."".$SI_unit, '',1,'L'); 
        } 

        //next line
        $y = $y+5;
        }*/ //commented by mai 09-18-2014
            }
        }
    }

    function Footer()
    {
        $lab_results = new Lab_Results();
        $sql = "SELECT remarks, med_tech_pid, pathologist_pid FROM seg_lab_resultdata WHERE refno='$this->refno' AND group_id='$this->group_id'  AND (ISNULL(`status`) OR `status`!='deleted');";

        $result = $lab_results->exec_query($sql);
        if ($result) {
            if ($person = $result->FetchRow()) {
                $pathologist_id = $person["pathologist_pid"];
                $med_tech_pid = $person["med_tech_pid"];
                $remarks = $person['remarks'];
                $sql = "SELECT CONCAT(IF(ISNULL(name_first), '', CONCAT(name_first, ' ')), IF(ISNULL(name_middle), '', name_middle), '. ', IF(ISNULL(name_last), '', name_last), ', ', IF(ISNULL(title), '', title)) as name from care_person WHERE care_person.pid = '" . $pathologist_id . "'";

                $result = $lab_results->exec_query($sql);
                if ($result != NULL && $person = $result->FetchRow())
                    $pathologist = $person["name"];
                else
                    $pathologist = "";

                $sql = "SELECT fn_get_personell_title_other('$pathologist_id') AS title";
                $result = $lab_results->exec_query($sql);
                if ($result != NULL && $person = $result->FetchRow())
                    $pathologist_title = $person["title"];
                else
                    $pathologist_title = "";

                $sql = "SELECT fn_get_pid_name('$med_tech_pid') as name";
                $result = $lab_results->exec_query($sql);
                if ($result != NULL && $person = $result->FetchRow())
                    $examiner = $person["name"];
                else
                    $examiner = "";

                $sql = "SELECT fn_get_personell_title_other('$med_tech_pid') AS title";
                $result = $lab_results->exec_query($sql);
                if ($result != NULL && $person = $result->FetchRow())
                    $examiner_title = $person["title"];
                else
                    $examiner_title = "";

                $sql = "SELECT license_nr FROM care_personell WHERE pid = '$med_tech_pid'";
                $result = $lab_results->exec_query($sql);
                if ($result != NULL && $person = $result->FetchRow())
                    $med_tech_lic = $person["license_nr"];
                else
                    $med_tech_lic = "";

                $sql = "SELECT license_nr FROM care_personell WHERE pid = '$pathologist_id'";
                $result = $lab_results->exec_query($sql);
                if ($result != NULL && $person = $result->FetchRow())
                    $patho_lic = $person["license_nr"];
                else
                    $patho_lic = "";
            }
        }

        $y = 34;

        $this->SetDrawColor(0, 0, 0);
        $this->SetXY(10, 130 + $y);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(20, 5, "Remarks: ", '', 0, 'L');
        $this->Cell(60, 5, $remarks, '', 1, 'L');

        $this->SetXY(10, 143 + $y);
        $this->SetFont('Arial', '', 11);
        $this->MultiCell(30, 5, strtoupper($this->refno), "B", "C", "0");
        $this->SetXY(10, 148 + $y);
        $this->Cell(10, 5, "", 0, 1, 'C');
        $this->SetFont('Arial', 'B', 11);
        $this->SetXY(10, 152 + $y);
        $this->Cell(30, 5, strtoupper("LAB NO."), 0, 0, 'C');

        $this->SetXY(52, 143 + $y);
        //$this->Image('../../gui/img/logos/pathologist_juanito_signature.jpg',75,134 + $y,20,20);
        $this->SetFont('Arial', '', 11);
        $this->MultiCell(75, 5, strtoupper($pathologist . "" . $pathologist_title), "B", "C", "0");
        $this->SetXY(60, 148 + $y);
        $this->SetFont('Arial', '', 8);
        $this->Cell(60, 5, "Lic No. " . $patho_lic, 0, 1, 'C');
        $this->SetFont('Arial', 'B', 11);
        $this->SetXY(60, 152 + $y);
        $this->Cell(60, 5, strtoupper("PATHOLOGIST"), 0, 0, 'C');

        $this->SetXY(132, 143 + $y);
        $this->SetFont('Arial', '', 11);
        $this->MultiCell(68, 5, strtoupper($examiner . ", " . $examiner_title), "B", "C", "0");
        $this->SetXY(140, 148 + $y);
        $this->SetFont('Arial', '', 8);
        $this->Cell(60, 5, "Lic No. " . $med_tech_lic, 0, 1, 'C');
        $this->SetFont('Arial', 'B', 11);
        $this->SetXY(140, 152 + $y);
        $this->Cell(60, 5, strtoupper("EXAMINER"), 0, 0, 'C');
    }
}

$report = new RepGen_LabResults();
$report->AliasNbPages();
$report->Report();
