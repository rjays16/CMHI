<?php

namespace SegHis\models\insurance;
use SegHis\models\insurance\InsuranceProvider;

class EncounterInsurance extends \CareActiveRecord {

    const PHIC_ID = 18;

    public function tableName(){
        return "seg_encounter_insurance";
    }

    public function relations(){
        return array(
            'insuranceProvider' => array(self::BELONGS_TO,'SegHis\models\insurance\InsuranceProvider','hcare_id')
        );
    }

    public function rules(){ return array(); }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
