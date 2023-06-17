<?php 
// error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path . 'include/inc_jasperReporting.php');

$PHIC_CF1 = new PhilhealthCF1();
$PHIC_CF1->processPDFOutput();

/**
 * Handles CF1 form printing with PHIC Member and Patient's Personal details.
 * @author michelle
 * @author macoy
 */
class PhilhealthCF1
{
    var $is_member;
    var $encounter_nr = '';
    var $hcare_id = 0;
    var $base_url = '';

    const MEMBER = 1;
    const NON_MEMBER = 0;

    public function __construct()
    {
            global $db;
           
            $this->encounter_nr = $db->qstr($_GET['encounter_nr']);
            $this->is_member = $this->checkIfMember();
            $this->hcare_id = $db->qstr($_GET['id']);
    }

    /**
     * checks if phic member
     * @return array
     */
    public function checkIfMember()
    {
        global $db;
        $sql = "SELECT 
                    i.is_principal AS Member 
                FROM 
                    care_person_insurance AS i
                LEFT JOIN care_encounter e 
                    ON e.pid = i.pid
                WHERE e.encounter_nr = $this->encounter_nr";

        $result = $db->Execute($sql);
        if($result)
            $row = $result->FetchRow();
        return $row['Member'];
    }

    function getPrincipalNmFromTmp()
    {
        #updated by janken for getting additional information 11/11/2014
        global $db;
        $strSQL = "SELECT 
                        mi.member_lname AS LastName,
                        mi.member_fname AS FirstName,
                        mi.suffix AS suffix,
                        '' AS ThirdName,
                        mi.member_mname AS MiddleName,
                        mi.sex AS sex,
                        mi.insurance_nr AS IdNum,
                        mi.street_name AS Street,
                        sb.brgy_name AS Barangay,
                        sg.mun_name AS Municity,
                        sg.zipcode AS Zipcode,
                        sp.prov_name AS Province,
                        sc.country_name AS Country,
                        mi.birth_date AS date_birth,
                        mi.employer_name AS EmployerName,
                        mi.employer_no AS EmployerNo,
                        mi.`signatory_name` AS sName,
                        mi.`signatory_relation` AS sRelation,
                        mi.`signatory_date` AS sDate,
                        mi.`relation_type` AS relation,
                        mi.`landline_no` AS landline,
                        mi.`mobile_no` AS mobile,
                        mi.`email_address` AS email,
                        mi.`is_member`,
                        mi.`is_incapacitated`,
                        mi.`reason`,
                        fn_get_person_name_first_mi_last(mi.`pid`) AS full_name  
                    FROM seg_insurance_member_info AS mi 
                        INNER JOIN care_encounter AS ce 
                            ON mi.pid = ce.pid 
                        LEFT JOIN care_person AS p 
                            ON p.pid = mi.pid 
                        LEFT JOIN seg_country AS sc 
                            ON p.citizenship = sc.country_code 
                        LEFT JOIN seg_barangays AS sb 
                            ON sb.brgy_nr = mi.brgy_nr 
                        LEFT JOIN seg_municity AS sg 
                            ON sg.mun_nr = mi.mun_nr 
                        LEFT JOIN seg_provinces AS sp 
                            ON sp.prov_nr = sg.prov_nr 
                    WHERE ce.encounter_nr = $this->encounter_nr";
        #echo $strSQL; die();
        if ($result = $db->Execute($strSQL)) {
            if ($result->RecordCount()) {
                return $result;
            }
        }
        return false;
    }

    function getMembersData()
    {
        #updated by janken for getting additional information 11/10/2014
        global $db;
        $sql_1 = "SELECT 
                    p.name_last AS LastName,
                    p.name_first AS FirstName,
                    p.name_2 AS SecondName,
                    p.name_3 AS ThirdName,
                    p.name_middle AS MiddleName,
                    p.suffix AS suffix,
                    i.insurance_nr AS IdNum,
                    p.street_name AS Street,
                    sb.brgy_name AS Barangay,
                    sg.mun_name AS Municity,
                    sg.zipcode AS Zipcode,
                    sp.prov_name AS Province,
                    sc.country_name AS Country,
                    i.is_principal AS Member,
                    p.date_birth,
                    p.sex,
                    mi.employer_name AS EmployerName,
                    mi.employer_no AS EmployerNo,
                    mi.`signatory_name` AS sName,
                    mi.`signatory_relation` AS sRelation,
                    mi.`signatory_date` AS sDate,
                    mi.`relation_type` AS relation,
                    mi.`landline_no` AS landline,
                    mi.`mobile_no` AS mobile,
                    mi.`email_address` AS email,
                    mi.`is_member`,
                    mi.`is_incapacitated`,
                    mi.`reason`,
                    fn_get_person_name_first_mi_last(mi.`pid`) AS full_name  
                FROM care_person AS p 
                    INNER JOIN care_person_insurance AS i 
                        ON i.pid = p.pid 
                    LEFT JOIN seg_insurance_member_info AS mi
                        ON mi.pid = p.pid
                    LEFT JOIN care_encounter AS e 
                        ON e.pid = p.pid 
                    LEFT JOIN seg_barangays AS sb 
                        ON sb.brgy_nr = p.brgy_nr 
                    LEFT JOIN seg_municity AS sg 
                        ON sg.mun_nr = sb.mun_nr 
                    LEFT JOIN seg_provinces AS sp 
                        ON sp.prov_nr = sg.prov_nr
                    LEFT JOIN seg_country AS sc 
                        ON p.citizenship = sc.country_code 
				WHERE i.hcare_id = $this->hcare_id 
                    AND i.is_principal = 1 
                    AND e.encounter_nr = $this->encounter_nr";
        #echo $sql_1; die();
        if ($result = $db->Execute($sql_1)) {
            if ($result->RecordCount()) {
                return $result;
            }
        }
        return false;
    }

    function getPatientsData()
    {
        #updated by janken for getting additional information 11/11/2014
        global $db;
        $sql = "SELECT 
                    p.name_last AS LastName,
                    p.name_first AS FirstName,
                    p.name_2 AS SecondName,
                    p.name_3 AS ThirdName,
                    p.name_middle AS MiddleName,
                    p.suffix AS Suffix,
                    p.date_birth,
                    p.sex,
                    mi.relation AS Relation,
                    mi.insurance_nr AS IdNum,
                    mi.`signatory_name` AS sName,
                    mi.`signatory_relation` AS sRelation,
                    mi.`signatory_date` AS sDate,
                    mi.`relation_type` AS relation,
                    mi.`landline_no` AS landline,
                    mi.`mobile_no` AS mobile,
                    mi.`email_address` AS email,
                    mi.`is_member`,
                    mi.`is_incapacitated`,
                    mi.`reason`,
                    fn_get_person_name_first_mi_last(mi.`pid`) AS full_name,
                    cpi.is_principal  
                FROM care_person AS p 
                    LEFT JOIN care_encounter AS e 
                        ON e.pid = p.pid 
                    LEFT JOIN seg_insurance_member_info AS mi 
                        ON mi.pid = e.pid 
                    LEFT JOIN care_person_insurance AS cpi 
                        ON cpi.pid = p.pid 
                    LEFT JOIN seg_encounter_insurance AS i 
                        ON i.encounter_nr = e.encounter_nr 
                WHERE i.hcare_id = $this->hcare_id 
                    AND e.encounter_nr = $this->encounter_nr"; 


        // echo $sql; die();
        $sql2 = "SELECT p.name_last AS LastName, p.name_first AS FirstName, p.name_2 AS SecondName, p.name_3 
                 AS ThirdName, p.name_middle AS MiddleName, p.date_birth, p.sex, mi.relation AS Relation, 
                 mi.insurance_nr AS IdNum, fn_get_person_name_first_mi_last(mi.`pid`) AS full_name 
                 FROM care_person AS p LEFT JOIN care_encounter AS e ON e.pid = p.pid 
                 LEFT JOIN seg_insurance_member_info AS mi ON mi.pid = e.pid LEFT JOIN care_person_insurance 
                 AS cpi ON cpi.pid = p.pid LEFT JOIN seg_encounter_insurance AS i 
                 ON i.encounter_nr = e.encounter_nr WHERE i.hcare_id = $this->hcare_id 
                 AND e.encounter_nr = $this->encounter_nr";

        $result2 = $db->Execute($sql);

        $result2_f = $db->Execute($sql2);
        $pat = $result2 == true ? $result2->FetchRow() : $result2_f->FetchRow();
        $patient_is_member = ($this->is_member == PhilhealthCF1::MEMBER) ? PhilhealthCF1::MEMBER : PhilhealthCF1::NON_MEMBER;

        return array_merge($pat, array('patient_is_member' => $patient_is_member));
    }

    function processPDFOutput()
    {

        if ($this->is_member) {
            $result = $this->getMembersData();
            #echo '1'; exit();

        }else{
                if (!($result = $this->getPrincipalNmFromTmp())) 
                    $result = false;
                     #echo '3'; exit();
                
            #Fetch Patient's data
            if (!($patient = $this->getPatientsData())){
                $patient = array();
            }
           
        }

        $top_dir = 'modules';
        $baseurl = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'off' ? 'https' : 'http',
            "127.0.0.1",
            substr(dirname($_SERVER["REQUEST_URI"]), 0, strpos($_SERVER["REQUEST_URI"], $top_dir))
        );

        #Initializations
        $patientArr = array();
        $params = array();
        $data[0] = array();
        $data[0]['image_01'] = $baseurl . "images/phic_logo.png";

        if (!empty($patient)) {

                #patient's Birthdate
                $patientBdate = explode('-', $patient['date_birth']);
                $patient_gender_f= ($patient['sex'] == 'f') ? "X" : "";
                $patient_gender_m = ($patient['sex'] == 'm') ? "X" : "";

                #patient's Ralation
                if($patient['Relation']== 'C'){
                    $is_child = "X";

                }elseif($patient['Relation']== 'P'){
                    $is_parent = "X";
                    
                }elseif($patient['Relation']== 'S'){
                    $is_spouse = "X";
                }

                $patientID = str_split($patient['IdNum']);
                #echo $patientID[0]; exit();

                $patientArr = array(
                    'patient_pin'            => $patientID[0],
                    'patient_pin_1'          => $patientID[1],
                    'patient_pin_2'          => $patientID[2],
                    'patient_pin_3'          => $patientID[3],
                    'patient_pin_4'          => $patientID[4],
                    'patient_pin_5'          => $patientID[5],
                    'patient_pin_6'          => $patientID[6],
                    'patient_pin_7'          => $patientID[7],
                    'patient_pin_8'          => $patientID[8],
                    'patient_pin_9'          => $patientID[9],
                    'patient_pin_10'         => $patientID[10],
                    'patient_pin_11'         => $patientID[11],
                    'patient_birth_date'    => $patientBdate[1] . ' ' . $patientBdate[2] . ' ' . $patientBdate[0],
                    'patient_fullname'      => strtoupper($patient['LastName']).' '.strtoupper($patient['FirstName']).' '.strtoupper($patient['MiddleName']),
                     'patient_name_last'     => strtoupper($patient['LastName']),
                     'patient_name_last'     => strtoupper($patient['LastName']),
                     'patient_name_first'    => strtoupper($patient['FirstName']),
                     'patient_suffix'        => '',
                     'patient_name_middle'   => strtoupper($patient['MiddleName']),
                    'patient_relation_c'    => strtoupper($is_child),
                    'patient_relation_p'    => strtoupper($is_parent),
                    'patient_relation_s'    => strtoupper($is_spouse),
                    'patient_gender_f'      => strtoupper($patient_gender_f),
                    'patient_gender_m'      => strtoupper($patient_gender_m)
                );
        }

        if ($result) {

            $mem = $result->FetchRow();   
            // var_dump($mem);die;
            $memID = str_split($mem['IdNum']);
            #echo $memID[0]; exit();

            $pen = str_split($mem['EmployerNo']);
            #echo $memID[0]; exit();

            #Membership --------------------
            // if($mem['Member']==1){
            if($this->is_member){
                $is_member = "X";
            }else{
                $is_dependent = "X";
            }

            #member's Birthdate ------------
            $memBdate = explode('-', $mem['date_birth']);         

            if(($mem['Barangay'] == 'NOT PROVIDED') || ($mem['Province'] == 'NOT PROVIDED')){
                    $Barangay= '';
                    $Province= '';
            }else{
                    $Barangay = $mem['Barangay']; 
                    $Province = $mem['Province'];
            }

            #member's gender ---------------
            $member_gender_f = ($mem['sex'] == 'f') ? "X" : "";
            $member_gender_m = ($mem['sex'] == 'm') ? "X" : "";

            #added by janken 11/10/2014
            #signatory information------
            if($this->is_member){
                $signatory_member_name = strtoupper($mem['FirstName'].' '.$mem['MiddleName'].' '.$mem['LastName'].' '.$mem['suffix']);
                $signatory_member_relation = $mem['sRelation'];
                $signatory_member_relation_type = $mem['relation'];
                if($mem['sDate']){
                     $signatory_member_date = explode('-', $mem['sDate']);
                }else{
                      $signatory_member_date = explode('-', '    -  -  ');
                }
               
                $signatory_nonmember_date = explode('-', '    -  -  ');
                $is_member = 'X';
                $is_nonmember = '';
            }
            else{
                $signatory_nonmember_name = strtoupper($mem['sName']);
                $signatory_nonmember_relation = $mem['sRelation'];
                $signatory_nonmember_relation_type = $mem['relation'];
                if($mem['sDate']){
                     $signatory_nonmember_date = explode('-', $mem['sDate']);
                }else{
                     $signatory_nonmember_date = explode('-', '    -  -  ');
                }
                $signatory_member_date = explode('-', '    -  -  ');
                $is_member = '';
                $is_nonmember = '';
                
                if($mem['is_incapacitated']){
                    $incap = 'X';
                    $other_reason = '';
                }
                else{
                    $incap = '';
                    $other_reason = '';
                }

                $reasons = $mem['reason'];
            }

            $is_spouse = ($mem['relation'] == 'S') ? "X" : "";
            $is_child = ($mem['relation'] == 'C') ? "X" : "";
            $is_other = ($mem['relation'] == 'O') ? "X" : "";
            $is_parent = ($mem['relation'] == 'P') ? "X" : "";
            $is_sibling = ($mem['relation'] == 'B') ? "X" : "";
            $specify = $mem['sRelation'];
          
            $params = array(
                'member_pin'            => $memID[0],
                'member_pin_1'          => $memID[1],
                'member_pin_2'          => $memID[2],
                'member_pin_3'          => $memID[3],
                'member_pin_4'          => $memID[4],
                'member_pin_5'          => $memID[5],
                'member_pin_6'          => $memID[6],
                'member_pin_7'          => $memID[7],
                'member_pin_8'          => $memID[8],
                'member_pin_9'          => $memID[9],
                'member_pin_10'         => $memID[10],
                'member_pin_11'         => $memID[11],
                'member_fullname'       => strtoupper($mem['LastName']) .' '.strtoupper($mem['FirstName'] . ' ' . $mem['SecondName']) .' '.strtoupper($mem['suffix']).' '.strtoupper($mem['MiddleName']),
                 'name_last'             => strtoupper($mem['LastName']),
                 'name_first'            => strtoupper($mem['FirstName'] . ' ' . $mem['SecondName']),
                 'suffix'                => strtoupper($mem['suffix']),
                 'name_middle'           => strtoupper($mem['MiddleName']),
                'birth_date'            => strtoupper($memBdate[1] . ' ' . $memBdate[2] . ' ' . $memBdate[0]),
                'member_gender_f'       => strtoupper($member_gender_f),
                'member_gender_m'       => strtoupper($member_gender_m),
                'unit'                  => '',
                'building_name'         => '',
                'street'                => strtoupper($mem['Street']),
                'subdivision'           => '',
                'barangay'              => strtoupper($Barangay),
                'city'                  => strtoupper($mem['Municity'] ),
                'province'              => strtoupper($Province),
                'country'               => strtoupper($mem['Country']),
                'zipcode'               => strtoupper($mem['Zipcode']),
                'landline_no'           => $mem['landline'],
                'mobile_no'             => $mem['mobile'],
                'email_address'         => $mem['email'],
                'patient_is_member_y'   => strtoupper($is_member),
                'patient_is_member_n'   => strtoupper($is_dependent),
                'pen'                   => $pen[0],
                'pen_1'                 => $pen[1],
                'pen_2'                 => $pen[2],
                'pen_3'                 => $pen[3],
                'pen_4'                 => $pen[4],
                'pen_5'                 => $pen[5],
                'pen_6'                 => $pen[6],
                'pen_7'                 => $pen[7],
                'pen_8'                 => $pen[8],
                'pen_9'                 => $pen[9],
                'pen_10'                => $pen[10],
                'pen_11'                => $pen[11],
                'is_member'             => $is_member,
                'is_nonmember'          => $is_nonmember,
                'is_spouse'             => $is_spouse,
                'is_child'              => $is_child,
                'is_other'              => $is_other,
                'is_parent'             => $is_parent,
                'is_sibling'            => $is_sibling,
                'specify'               => $specify,
                'is_incapacitated'      => strtoupper($incap),
                'is_other_reason'       => strtoupper($other_reason),
                'reason'                => strtoupper($reasons),
                'emp_business_name'     => strtoupper($mem['EmployerName']),
                'signatory_name_member' => strtoupper($signatory_member_name),
                'signatory_name_nonmember' => strtoupper($signatory_nonmember_name),
                'signatory_date_member' => strtoupper($signatory_member_date[1] . ' ' . $signatory_member_date[2] . ' ' . $signatory_member_date[0]),
                'signatory_date_nonmember' => strtoupper($signatory_nonmember_date[1] . ' ' . $signatory_nonmember_date[2] . ' ' . $signatory_nonmember_date[0])     
            );
        } else { 
            echo 'FAILED!';
        }

        $params = array_merge($params, $patientArr);
        #render report -------------
        showReport('PHIC_CF1', $params, $data, 'PDF');
    }

}