<?php

class ReferralController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
			array('bootstrap.filters.BootstrapFilter'),
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			// array('allow',  // allow all users to perform 'index' and 'view' actions
			// 	'actions'=>array('index','view'),
			// 	'users'=>array('*'),
			// ),
			// array('allow', // allow authenticated user to perform 'create' and 'update' actions
			// 	'actions'=>array('create','update'),
			// 	'users'=>array('@'),
			// ),
			// array('allow', // allow admin user to perform 'admin' and 'delete' actions
			// 	'actions'=>array('admin','delete'),
			// 	'users'=>array('admin'),
			// ),
			// array('deny',  // deny all users
			// 	'users'=>array('*'),
			// ),
		);
	}

	public function actionIndex()
	{
		$model = new SocialReferrals;

		$model->setAttributes($_GET['SocialReferrals']);
		$this->render('index', array(
			'model' => $model,
		));

	}

	public function actionCreate()
	{
		$model = new SocialReferrals;

		if(isset($_POST['SocialReferrals'])){
			$_POST['SocialReferrals']['create_id'] = $_SESSION['sess_login_username'];
			$_POST['SocialReferrals']['create_dt'] = date('Y-m-d H:i:s');
			$_POST['SocialReferrals']['history'] = 'Created ' . date('Y-m-d H:i:s') . ' | ' . $_SESSION['sess_login_username'] . "\n";
			$model->attributes = $_POST['SocialReferrals'];

			if($model->save()){
				Yii::app()->user->setFlash('success', 'Successfully referred the patient.');
				$this->redirect(array('view', 'id' => $model->refer_id));
			}
			else{
				Yii::app()->user->setFlash('error', 'Error in referring the patient.');
			}
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	public function actionView($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['SocialReferrals'])){
			$_POST['SocialReferrals']['modify_id'] = $_SESSION['sess_login_username'];
			$_POST['SocialReferrals']['modify_dt'] = date('Y-m-d H:i:s');
			$model->history .= 'Update ' . date('Y-m-d H:i:s') . ' | ' . $_SESSION['sess_login_username'] . "\n";
			$model->attributes = $_POST['SocialReferrals'];

			if($model->save()){
				Yii::app()->user->setFlash('success', 'Successfully updated patient\'s referral form.');
			}
			else{
				Yii::app()->user->setFlash('error', 'Error in updating patient\'s referral form.');
			}
		}

		$this->render('view', array(
			'model' => $model,
		));
	}

	public function loadModel($id)
	{
		$model=SocialReferrals::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function actionPatients()
	{

		$sql = "SELECT
				  ce.encounter_nr,
				  ce.pid,
				  ce.encounter_date,
				  ce.encounter_type,
				  fn_get_age (NOW(), cp.date_birth) AS age,
				  ce.current_dept_nr,
				  cp.sex,
				  cp.civil_status,
				  fn_get_person_name_first_mi_last(cp.pid) AS patient,
				  fn_get_complete_address2(cp.pid) AS address,
				  ce.er_opd_diagnosis
				FROM
				  care_encounter ce
				  INNER JOIN care_person cp
					ON cp.pid = ce.pid
				  INNER JOIN seg_socserv_patient ssp
				  	ON ssp.encounter_nr = ce.encounter_nr
				WHERE (ce.pid LIKE '" . $_GET['hrn'] . "%'
				OR ce.encounter_nr LIKE '" . $_GET['hrn'] . "%')
				AND ce.status NOT IN ('deleted', 'inactive', 'hidden', 'void')
				ORDER BY ce.encounter_date DESC
				LIMIT 1 ";

		$patient = Yii::app()->db->createCommand($sql)->queryAll();

		echo CJSON::encode($patient);

	}

	public function actionCheckMss($enc)
	{
		$hasMss = "SELECT mss_no FROM seg_socserv_patient WHERE encounter_nr = '".$enc."'";

		$response = Yii::app()->db->createCommand($hasMss)->queryAll();

		header('Content-type: application/json');
		echo CJSON::encode($response);
	}
}