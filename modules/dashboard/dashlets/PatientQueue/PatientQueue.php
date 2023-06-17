<?php
/**
*Dashlet for Patient Queue
*Created by Maimai
*Created on 10-13-2014
*/

require './roots.php';
require_once $root_path.'include/care_api_classes/dashboard/Dashlet.php';
require_once $root_path.'include/care_api_classes/dashboard/DashletSession.php';
require_once $root_path.'gui/smarty_template/smarty_care.class.php';

class PatientQueue extends Dashlet{
	protected static $name = 'Patient Queue';
	protected static $icon = 'group.png';
	protected static $group = '';

	public function __construct($id = null){
		parent::__construct($id);
	}

	public function init(){
		parent:: init(Array(
			'contentHeight' => 'auto',
			'pageSize' => 10
		));
	}

	public function processAction(DashletAction $action){
		global $db;
		$response = new DashletResponse;
		if($action->is('save')){
			$data = (array) $action->getParameter('data');
			foreach($data as $i=>$item){
				if($item['name'] == 'pageSize'){
					$pageSize = $item['value'];
				}
			}

			$this->preferences->set('pageSize', $pageSize);
			$this->setMode(DashletMode::getViewMode());
			$updateOk = $this->update();

			if(false !== $updateOk){
				$response->call("Dashboard.dashlets.refresh", $this->getId());
			}else{
				$response->alert("Error saving ". $query);
			}

		}else if($action->is('openFile')){
			$file = $action->getParameter('file');
			$session = DashletSession::getInstance(DashletSession::SCOPE_DASHBOARD, $_SESSION['activeDashboard']);
			$session->set('ActivePatientFile', $file);
			$response->execute("$('PatientList-".$this->getId()."').list.reload()");
			$response->classRefresh('PatientInformation');
			$response->classRefresh('PatientHistory');
			$response->classRefresh('PatientLabResults');
			$response->classRefresh('PatientRadioResults');
			$response->classRefresh('DoctorsNotes');
			$response->classRefresh('RxWriter');
			$response->classRefresh('PatientResultQueue');
			$response->classRefresh('PatientQueue');
		}else{
			$response->extend(parent::processAction($action));
		}

		return $response;
	}

	public function render($renderParams=null){
		global $root_path;
		if($renderParams['mode']){
			$mode = $renderParams['mode'];
		}else{
			$mode = $this->getMode();
		}

		if($mode->is(DashletMode:: VIEW_MODE)){
			$smarty = new smarty_care('common');
			$dashletSmarty = array(
				'id' => $this->getId()
			);

			$smarty->assign('dashlet', $dashletSmarty);
			$preferencesSmarty = array(
				'pageSize'=> $this->preferences->get('pageSize')
			);

			$smarty->assign('settings', $preferencesSmarty);
			return $smarty->fetch($root_path.'modules/dashboard/dashlets/PatientQueue/templates/ListView.tpl');
		}else if($mode->is(DashletMode:: EDIT_MODE)){
			$smarty = new smarty_care('common');
			$dashletSmarty = array(
				'id' => $this->getId()
			);

			$smarty->assign('dashlet', $dashletSmarty);
			$preferencesSmarty = array(
				'pageSize' => $this->preferences->get('pageSize')
			);

			$smarty->assign('settings', $preferencesSmarty);
			return $smarty->fetch($root_path.'modules/dashboard/dashlets/PatientQueue/templates/Config.tpl');
		}else{
			return parent::render($renderParams);
		}
	}
}