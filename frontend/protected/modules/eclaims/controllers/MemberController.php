<?php

/**
 *
 * MemberPinController.php
 */

/**
 * Controller for Module 1a (Get PhilHealth Identification Number)
 *
 * @package eclaims.controllers
 */

class MemberController extends Controller
{

    /**
     * @see CController::filters
     */
    public function filters()
    {
        return array(
            'accessControl',
            array('bootstrap.filters.BootstrapFilter')
        );
    }

    /**
     * @todo Create GetPIN Actions, to separate accessRules for sudomanage and view
     */
    public function accessRules()
    {
        return array(
            array(
                'deny',
                'actions' => array('index'),
                'users' => array('?')
            ),
            array(
                'deny',
                'expression' => '!Yii::app()->user->checkPermission("eclaims")',
            ),
            array(
                'deny',
                'actions' => array('manageInsuranceToBilling'),
                'expression' => '!Yii::app()->user->checkPermission("member_sudomanage")',
            ),
            array(
                'allow',
                'actions' => array('index'),
                'users' => array('@')
            ),
        );
    }

    /**
     *
     * @param CAction $actione
     * @return boolean
     */
    public function beforeAction($action)
    {
        $this->breadcrumbs['Get Member PIN'] = array('member/getPIN');
        return parent::beforeAction($action);
    }


    /**
     *
     */
    public function actionSaveMember()
    {
        Yii::import('eclaims.models.EclaimsPerson');
        Yii::import('phic.models.EclaimsPhicMember');

        $request = Yii::app()->getRequest();
        $pid = $request->getQuery('pid');

        $person = EclaimsPerson::model()->findByPk($pid);
        if (empty($person)) {
            // hmmm
        } else {
            if (empty($person->member)) {
                $person->member = new EclaimsPhicMember;
            }
        }

        // Perform AJAX validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'member-form') {
            echo CActiveForm::validate($person->member);
            Yii::app()->end();
        }
    }

    /**
     * Features
     * - Add insurance to the billing
     *
     * @params $pid Person::pid
     * @params $action
     * For now, this can be "add" or "remove"
     *
     * @author Jolly Caralos
     */
    public function actionManageInsuranceToBilling()
    {
        Yii::import('eclaims.models.EclaimsPerson');
        Yii::import('eclaims.models.EclaimsPhicMember');
        Yii::import('eclaims.models.EclaimsEncounterInsurance');

        $request = Yii::app()->getRequest();
        $pid = $request->getQuery('pid');
        $action = $request->getQuery('action');

        $person = EclaimsPerson::model()->findByPk($pid);
        if ($person) {
            if (!empty($person->latestEncounter)) {
                switch ($action) {
                    case 'add':
                        $encounterInsurance = EclaimsEncounterInsurance::model()->createEncounterInsurance($person->latestEncounter);
                        if (!$encounterInsurance->getErrors()) {
                            Yii::app()->user->setFlash('success', '<strong>Success!</strong> PhilHealth Insurance was finally added to the billing.');
                        } else {
                            Yii::app()->user->setFlash('error', '<strong>Error!</strong> Failed to add PhilHealth Insurance to the billing!');
                        }
                        break;
                    case 'remove':
                        $isDeleted = EclaimsEncounterInsurance::model()->removeEncounterInsurance($person->latestEncounter);
                        if ($isDeleted) {
                            Yii::app()->user->setFlash('success', '<strong>Success!</strong> PhilHealth Insurance was finally remove from the billing.');
                        } else {
                            Yii::app()->user->setFlash('error', '<strong>Error!</strong> Failed to remove PhilHealth Insurance to the billing!');
                        }
                        break;
                    default:
                        # Do nothing
                        break;
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error!</strong> Person encounter record not found!');
            }
        } else {
            Yii::app()->user->setFlash('error', '<strong>Error!</strong> Person record not found!');
        }

        $redirectUrl = $request->getQuery('redirectUrl');
        if (empty($redirectUrl)) {
            $this->redirect($this->createUrl('getPin', array(
                'pid' => $pid
            )));
        } else {
            $this->redirect($redirectUrl);
        }
    }

    /**
     * Action Getpin: saves the input from the form
     * Features:
     * - Get PIN via web service call so HITP
     * - Save Member's PIN and other Data
     */
    public function actionGetPin()
    {
        Yii::import('eclaims.models.MemberPinForm');
        Yii::import('eclaims.models.EclaimsPerson');
        Yii::import('eclaims.models.EclaimsPhicMember');
        Yii::import('eclaims.models.Member');
        Yii::import('eclaims.models.EclaimsEncounterInsurance');

        $request = Yii::app()->getRequest();
        $pid = $request->getQuery('pid');
        $model = new MemberPinForm;

        if (isset($_POST['pid']) && isset($_POST['tab']) && $_POST['tab'] == 'patient') {
            $pid = $_POST['pid'];
        }

        if ($pid) {
            $person = EclaimsPerson::model()->findByPk($pid);
        }

        if (empty($person)) {
            $person = new EclaimsPerson;
        }

        if (empty($person->latestEncounter)) {
            $person->latestEncounter = new EclaimsEncounter;
        }

        if (empty($person->phicMember)) {
            $person->phicMember = new EclaimsPhicMember;
            $person->phicMember->pid = $person->pid;
        }

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'member-form') {
            echo CActiveForm::validate($person->phicMember);
            Yii::app()->end();
        }

        if (isset($_POST['EclaimsPhicMember']) && is_array($_POST['EclaimsPhicMember'])) {
            #change date format
            $_POST['EclaimsPhicMember']['birth_date'] = str_replace('-', '/', $_POST['EclaimsPhicMember']['birth_date']);
            $_POST['EclaimsPhicMember']['birth_date'] = date('Y-m-d', strtotime($_POST['EclaimsPhicMember']['birth_date']));


            if (!$person->isNewRecord) {
                $person->phicMember->attributes = $_POST['EclaimsPhicMember'];

                $memberExists = EclaimsPhicMember::model()->findByPk($person->latestEncounter->encounter_nr);
                if (empty($memberExists)) {
                    $person->phicMember->setScenario('insert');
                    $person->phicMember->setIsNewRecord(true);
                }

                $person->phicMember->encounter_nr = $person->latestEncounter->encounter_nr;

                $phicMember = new PhicMember();
                $phicEncounter = $phicMember->findByPk($person->latestEncounter->encounter_nr);
                $phicSaved = true;
                if ($phicEncounter) {
                    $phicEncounter->patient_pin = $_POST['EclaimsPhicMember']['patient_pin'];
                    $phicSaved = $phicEncounter->save();
                }

                #monmon : temporary workaround
                #updating seg_encounter_memcategory value
                global $db;
                $memcode = $_POST['EclaimsPhicMember']['member_type'];
                $db->Execute('UPDATE seg_encounter_memcategory set memcategory_id = (SELECT memcategory_id FROM seg_memcategory WHERE memcategory_code = ' . $db->qstr($memcode) . ')');
                #end monmon

                $transaction = Yii::app()->getDb()->beginTransaction();
                try {

                    if ($person->phicMember->save()) {
                        $transaction->commit();
                        Yii::app()->user->setFlash('success', '<b>Great!</b> The member information was successfully saved!');
                        $this->redirect($this->createUrl('getPin', array('pid' => $pid)));
                    } else {
                        throw new CDbException("Record was not saved!", 500);
                    }
                } catch (CDbException $ex) {
                    $_exception = true;
                }
                if (isset($_exception)) {
                    if ($transaction->active) {
                        $transaction->rollback();
                    }
                    $model->addErrors($person->phicMember->getErrors());
                }
            } else {
                throw new CHttpException('Cannot update the member information of a non-existent person!');
            }
        }


        if (isset($_POST['tab'])) {

            if ($_POST['tab'] == 'walkin') {
                $model->attributes = $_POST['MemberPinForm'];
            } else {
                $model->attributes = $person->phicMember->getPinParams();
            }

            if ($model->validate()) {
                Yii::import('eclaims.services.ServiceExecutor');
                $service = new ServiceExecutor(
                    array(
                        'endpoint' => 'hie/eligibility/getpin',
                        'params' => $model->getPinParams()
                    )
                );

                try {
                    $response = $service->execute();
                    if ($response['success']) {
                        Yii::app()->user->setFlash('success', '<strong>PHIC PIN</strong><br/><h3>' . $response['data'] . '</h3>');

                        if ($_POST['tab'] !== 'walkin' && $person->phicMember->pid) {
                            $person->phicMember->insurance_nr = $response['data'];


                            $memberExists = EclaimsPhicMember::model()->findByPk($person->latestEncounter->encounter_nr);
                            if (empty($memberExists)) {
                                $person->phicMember->setScenario('insert');
                                $person->phicMember->setIsNewRecord(true);
                            }
                            $person->phicMember->encounter_nr = $person->latestEncounter->encounter_nr;
                            $person->phicMember->save();
                        }
                    } else {

                        if (empty($response['code']) && empty($response['data'])) {
                            Yii::app()->user->setFlash('warning', 'Member PIN not found: <strong>' . $response['data'] . '</strong>');
                        } else {
                            Yii::app()->user->setFlash('error', '<strong>Web service error:</strong> ' . $response['message']);
                        }
                    }
                } catch (ServiceCallException $e) {
                    Yii::app()->user->setFlash('error', '<strong>Web service error:</strong> ' . $e->getMessage());
                }
            } else {
            }
        }
        //added by Jasper Ian Q. Matunog 11/25/2014
        $phic_mem = new EclaimsPhicMember();
        $latestEncounter = $person->latestEncounter->phicMember ?: $phic_mem;
        $hasFinalBill = $person->latestEncounter->bill->is_final && is_null($person->latestEncounter->bill->is_deleted);
        $this->render('getPin', array(
            'model' => $model,
            'person' => $person,
            'member' => $latestEncounter,
            'hasFinalBill' => $hasFinalBill,
        ));
    }

    /**
     * Ajax action, checks if the relation is "M".
     * If TRUE, return the person data. ElSE null.
     *
     * @param $pid
     * @param $relation
     *
     * @author Jolly Caralos
     */
    public function actionGetPersonData()
    {
        $request = Yii::app()->getRequest();
        $pid = $request->getQuery('pid');
        $relation = $request->getQuery('relation');

        if ($pid) {
            Yii::import('eclaims.models.EclaimsPerson');
            $person = EclaimsPerson::model()->findByPk($pid);

            echo CJSON::encode(array(
                'member_fname' => $person->getNameFirst(),
                'member_mname' => $person->name_middle,
                'member_lname' => $person->name_last,
                'suffix' => $person->getSuffix(),
                'birth_date' => $person->date_birth,
                'sex' => $person->sex,
                'insurance_nr' => $person->phicMember->insurance_nr,
            ));
        }
        Yii::app()->end();
    }
}
