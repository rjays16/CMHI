<?php
Yii::import('or.models.*');

class PackageController extends Controller
{
    public $layout = '/layouts/main';

    public function actionIndex()
    {
        $package_price = 0;
        $packageModel = Packages::model()->findAllByPatientType($_GET['encounter_nr']);
        $packageList = CHtml::listData($packageModel, 'package_id', 'package_name');

        if(!empty($_GET['search'])){
            $packageDetailsModel = PackageDetails::model()->getPackageDetailsByName($_GET);
            $dataProvider =  new CArrayDataProvider(
                $packageDetailsModel, 
                array(
                    'pagination' => array(
                        'pageSize' => 5
                    ),
                    'keyField' => false
                )
            );

            foreach ($packageDetailsModel as $key => $value) {
                $package_price += ($value->price * $value->quantity);
            }
        }else{
            $dataProvider =  new CArrayDataProvider(
                array(), 
                array(
                    'pagination' => array(
                        'pageSize' => 5,
                    ),
                    'keyField' => false
                )
            );
        }

        if(!empty($_POST)){
            $save = $this->savePackageDetails($_POST);
            $this->redirect($this->createUrl('done', array('save' => $save?1:0)));
        }

        $insurance = EncounterInsurance::model()->findByAttributes(array('encounter_nr' => $_GET['encounter_nr']));
        $pharmacyAreas = PharmacyArea::model()->findAll();

        $this->render(
            'index',
            array(
                'packageList' => $packageList,
                'dataProvider' => $dataProvider,
                'encounter_nr' => $_GET['encounter_nr'],
                'package_price' => $package_price,
                'insurance' => $insurance,
                'pharmacyAreas' => $pharmacyAreas
            )
        );
    }

    public function actionDone(){
        $this->render(
            'done',
            array(
                'save' => $_GET['save']
            )
        );
    }

    private function _generateRefno(){
        return time().rand(10,99);
    }

    private function savePackageDetails($params){
        $packageModel = Packages::model()->findByPk($params['packageSelect']);
        $enc_nr = $params['encounter_nr'];
        $trans_type = $params['trans_type'];
        $charge_type = $params['charge_type'];
        $encounterModel = Encounter::model()->findByPk($enc_nr);
        $pharmacyArea = $params['pharmacy_area'];

        $pharmaHB = false;
        $pharmaHId = '';
        $labHB = false;
        $labHId = '';
        $radHB = false;
        $radHId = '';
        $miscHB = false;
        $miscHId = '';
        $saveOk = true;

        $transaction = Yii::app()->getDb()->beginTransaction();
        $entryNo = MiscService::model()->getEntry($enc_nr);

        $orRequest = new OrRequest();
        $or_refno = $this->_generateRefno();
        $orRequest->attributes = array(
            'or_refno' => $or_refno,
            'encounter_nr' => $enc_nr,
            'trans_type' => 0,
            // 'is_urgent' => 'Is Urgent',
            // 'dept_nr' => 'Dept Nr',
            // 'dr_nr' => 'Dr Nr',
            // 'or_type' => 'Or Type',
            // 'or_case' => 'Or Case',
            // 'request_flag' => '',
            'date_requested' => date("Y-m-d H:i:s"),
            'history' => 'Create: '.date("Y-m-d H:i:s").' = '.$_SESSION['sess_temp_userid'],
        );
        if(!$orRequest->save()){
            $transaction->rollBack();
            return false;
        }

        $orPackageUse = new OrPackageUse();
        $orPackageUse->or_refno = $or_refno;
        $orPackageUse->package_id = $packageModel->package_id;
        $orPackageUse->package_amount = 0;
        if($orPackageUse->save()){
            foreach ($packageModel->packageDetails as $key => $value) {
                $value->is_cash = $trans_type;
                switch ($value->item_purpose) {
                    case 'PH':
                        if(!$pharmaHB){
                            $pharmaHB = true;
                            $pharmaHId = $this->createPhHeader($encounterModel, $or_refno, $trans_type, $charge_type, $pharmacyArea);
                        }

                        if(empty($pharmaHId)){
                            $saveOk = false;
                            break;
                        }
                        $saveOk = $this->savePhDetails($pharmaHId, $value);
                        break;
                    case 'LB':
                        if(!$labHB){
                            $labHB = true;
                            $labHId = $this->createLbHeader($encounterModel, $or_refno, $trans_type, $charge_type);
                        }

                        if(empty($labHId)){
                            $saveOk = false;
                            break;
                        }
                        $saveOk = $this->saveLbDetails($labHId, $value);
                        break;
                    case 'RD':
                        if(!$radHB){
                            $radHB = true;

                            $rid = RadioId::model()->createNewRadioId($encounterModel->pid);
                            $radHId = $this->createRdHeader($encounterModel, $or_refno, $trans_type, $charge_type);
                        }

                        if(empty($radHId)){
                            $saveOk = false;
                            break;
                        }
                        $saveOk = $this->saveRdDetails($radHId, $value);
                        break;
                    case 'MISC':
                        if(!$miscHB){
                            $miscHId = $this->createMiscHeader($encounterModel, $trans_type);
                            $miscHB = true;
                        }

                        if(empty($miscHId)){
                            $saveOk = false;
                            break;
                        }
                        $saveOk = $this->saveMiscDetails($miscHId, $entryNo, $value);
                        break;
                    default:
                        continue;
                }


                $orPackagesItem = new OrPackagesItems();
                $orPackagesItem->seg_or_package_use_id = $orPackageUse->id;
                $orPackagesItem->or_refno = $or_refno;
                $orPackagesItem->package_id = $value->package_id;
                $orPackagesItem->item_code = $value->item_code;
                $orPackagesItem->qty = $value->quantity;
                $orPackagesItem->price = $value->getPrice();
                if(!$orPackagesItem->save()){
                    $saveOk = false;
                    break;
                }

                $orPackageUse->package_amount += ($value->price * $value->quantity);
                if(!$orPackageUse->save()){
                    $saveOk = false;
                    break;
                }

                if(!$saveOk)
                    break;
            }
        }else{
            $saveOk = false;
        }

        if($saveOk){
            $transaction->commit();
            return true;
        }
        else{
            $transaction->rollBack();
            return false;
        }
    }

    private function createPhHeader($encounterModel, $or_refno, $trans_type, $charge_type, $pharmacyArea){
        $pharmaOrdersRefno = PharmaOrders::model()->latest()->find()->refno + 1;
        $pharmaOrdersModel = new PharmaOrders();
        $pharmaOrdersModel->attributes = array(
            'refno' => $pharmaOrdersRefno,
            'orderdate' => date('Y-m-d H:i:s'),
            'pharma_area' => $pharmacyArea,
            'request_source' => $_GET['req_src'],
            'pid' => $encounterModel->pid,
            'encounter_nr' => $encounterModel->encounter_nr,
            'related_refno' => $or_refno,
            'ordername' => $encounterModel->person->fullname,
            'orderaddress' => $encounterModel->person->fullAddress,
            'charge_type' => $trans_type?null:$charge_type,
            'is_cash' => $trans_type,
            'serve_status' => 'S',
            'amount_due' => 0,
            'history' => 'Create: '.date("Y-m-d H:i:s").' = '.$_SESSION['sess_temp_userid']
        );

        if(!$pharmaOrdersModel->save())
            return null;

        return $pharmaOrdersRefno;
    }

    private function savePhDetails($pharmaOrdersRefno, $packageDetail){
        $saveOkP = true;

        $pharmaOrderItemsModel = new PharmaOrderItems();
        $pharmaOrderItemsModel->attributes = array(
            'refno' => $pharmaOrdersRefno,
            'bestellnum' => $packageDetail->item_code,
            'quantity' => $packageDetail->quantity,
            'pricecash' => $packageDetail->getPrice(),
            'pricecharge' => $packageDetail->getPrice(),
            'price_orig' => $packageDetail->getPrice(),
            'serve_remarks' => ' ',
            'serve_status' => 'S',
            'serve_dt' => date('Y-m-d H:i:s'),
        );

        if(!$pharmaOrderItemsModel->save())
            $saveOkP = false;

        if($saveOkP){
            $pharmaOrdersModel = PharmaOrders::model()->findByPk($pharmaOrdersRefno);
            $pharmaOrdersModel->amount_due += ($packageDetail->quantity *  $packageDetail->getPrice());
            return $pharmaOrdersModel->save();
        }
        else{
            return false;
        }
        
    }

    private function createMiscHeader($encounterModel, $trans_type){
        $miscServiceModel = new MiscService;
        $refno = $miscServiceModel->getPk(date('Y-m-d H:i:s'));
        $miscServiceModel->attributes = array(
            'refno' => $refno,
            'chrge_dte' => date('Y-m-d H:i:s'),
            'encounter_nr' => $encounterModel->encounter_nr,
            'pid' => $encounterModel->pid,
            'is_cash' => $trans_type,
            'request_source' => $_GET['req_src'],
            'history' => 'Create: '.date("Y-m-d H:i:s").' = '.$_SESSION['sess_temp_userid']
            // 'area' => 'Area',
        );
        if(!$miscServiceModel->save())
            return null;

        return  $refno;
    }

    private function saveMiscDetails($miscServRefno, $entryNo, $packageDetail){
        $tempModel = OtherServices::model()->findByPk($packageDetail->item_code);
        $miscServicedetailsModels = new MiscServiceDetails;
        $miscServicedetailsModels->attributes = array(
            'refno' => $miscServRefno,
            'service_code' => $tempModel->alt_service_code,
            'entry_no' => $entryNo,
            'account_type' => 0,
            'adjusted_amnt' => $packageDetail->getPrice(),
            'chrg_amnt' => $packageDetail->getPrice(),
            'quantity' => $packageDetail->quantity
        );

        if(!$miscServicedetailsModels->save())
            return false;
        else
            return true;
    }

    public function createLbHeader($encounterModel, $or_refno, $trans_type, $charge_type){
        $labTrackerModel = LabTracker::model()->find();
        $labServRefno = $labTrackerModel->last_refno + 1;

        $labServModel = new LabServ();
        $labServModel->attributes = array(
            'refno' => $labServRefno,
            'serv_dt' => date('Y-m-d'),
            'serv_tm' => date('H:i:s'),
            'encounter_nr' => $encounterModel->encounter_nr,
            'pid' => $encounterModel->pid,
            'history' => 'Create: '.date("Y-m-d H:i:s").' = '.$_SESSION['sess_temp_userid'],
            'ordername' => $encounterModel->person->fullname,
            'orderaddress' => $encounterModel->person->fullAddress,
            'source_req' => $_GET['req_src'],
            'grant_type' => $trans_type?null:strtolower($charge_type),
            'is_cash' => $trans_type,
            'ref_source' => 'LB',
            'status' => ' '
        );
        if(!$labServModel->save())
            return null;
        
        $labTrackerModel->last_refno = $labServRefno;
        if(!$labTrackerModel->save())
            return null;

        return $labServRefno;
    }

    public function saveLbDetails($labHId, $packageDetail){
        $labServdetailsModel = new LabServdetails();
        $labServdetailsModel->attributes = array(
            'refno' => $labHId,
            'service_code' => $packageDetail->item_code,
            'price_cash' => $packageDetail->getPrice(),
            'price_cash_orig' => $packageDetail->getPrice(),
            'price_charge' => $packageDetail->getPrice(),
            'quantity' => $packageDetail->quantity,
            'history' => 'Create: '.date("Y-m-d H:i:s").' = '.$_SESSION['sess_temp_userid'],
        );
        if(!$labServdetailsModel->save())
            return false;
        else
            return true;
    }

    public function createRdHeader($encounterModel, $or_refno, $trans_type, $charge_type){
        $radioServRefno = RadioServ::model()->latest()->find()->refno + 1;
        
        $radioServModel = new RadioServ();
        $radioServModel->attributes = array(
            'refno' => $radioServRefno,
            'request_date' => date('Y-m-d'),
            'request_time' => date('H:i:s'),
            'encounter_nr' => $encounterModel->encounter_nr,
            'pid' => $encounterModel->pid,
            'ordername' => $encounterModel->person->fullname,
            'orderaddress' => $encounterModel->person->fullAddress,
            'history' => 'Create: '.date("Y-m-d H:i:s").' = '.$_SESSION['sess_temp_userid'],
            'source_req' => $_GET['req_src'],
            'type_charge' => $trans_type?null:$charge_type,
            'is_cash' => $trans_type,
            'status' => ' ',
        );
        if(!$radioServModel->save())
            return null;

        return $radioServRefno;
    }

    public function saveRdDetails($radHId, $packageDetail){
        $careTestRequestRadioModel = new CareTestRequestRadio();
        $careTestRequestRadioRefno = CareTestRequestRadio::model()->latest()->find()->refno + 1;
        $careTestRequestRadioModel->attributes = array(
            'batch_nr' => $careTestRequestRadioRefno,
            'refno' => $radHId,
            'clinical_info' => ' ',
            'service_code' => $packageDetail->item_code,
            'price_cash' => $packageDetail->getPrice(),
            'price_cash_orig' => $packageDetail->getPrice(),
            'price_charge' => $packageDetail->getPrice(),
            'service_date' => date("Y-m-d H:i:s", '0000-00-00'),
            'history' => 'Create: '.date("Y-m-d H:i:s").' = '.$_SESSION['sess_temp_userid'],
            'status' => 'pending',
            'is_in_house' => 0,
            'request_doctor' => ' ',
            'request_date' => date('Y-m-d'),
            'encoder' => $_SESSION['sess_temp_userid'],
        );
        if(!$careTestRequestRadioModel->save())
            return false;
        else
            return true;
    }
    
}