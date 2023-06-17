<?php

class EclaimsEncounterInsurance extends EncounterInsurance {

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Creates an Encounter Insurance for PhilHealth
     * Flow:
     * - Finds the InsuranceProvider by InsuranceProvider::INSURANCE_PHIC
     * - If empty return null, else create EncounterInsurance
     * - Return EncounterInsurance
     * 
     * @author Jolly Caralos
     */
    public function createEncounterInsurance(Encounter $encounter) {
        // Yii::import('eclaims.models.EncounterMemcategory');
        Yii::import('phic.models.PhicMember');

        $insuranceProvider = InsuranceProvider::getProviderByShortFirmId(InsuranceProvider::INSURANCE_PHIC);
      
        $this->encounter_nr = $encounter->encounter_nr;


        $encounterInsurance = $this->getEncounterInsuranceByProvider($insuranceProvider);

        if(empty($encounterInsurance)) {
            $encounterInsurance = new EclaimsEncounterInsurance;
            $encounterInsurance->attributes = array(
                'encounter_nr' => $this->encounter_nr,
                'hcare_id' => $insuranceProvider->hcare_id,
            );
            $encounterInsurance->save();

            #added by monmon
            $phicmember = PhicMember::model()->findByPk($this->encounter_nr);

            // if($phicmember->member_type){
                global $db;
                $memCategory = $db->GetOne('SELECT memcategory_id FROM seg_memcategory WHERE memcategory_code=' . $db->qstr($phicmember->member_type));

                #temporary workaround
                $db->Execute("INSERT INTO seg_encounter_memcategory (encounter_nr,memcategory_id) VALUES ('$this->encounter_nr',$memCategory)");

                #uncomment this if problem was found :(
                /*$encounterMemcategory = new EncounterMemcategory;
                $encounterMemcategory->attributes = array(
                    'encounter_nr' => $this->encounter_nr,
                    'memcategory_id' => $memCategory
                );
                $encounterMemcategory->save();*/
            // }
           
        }
        return $encounterInsurance;
    }

    /**
     * @param $encouter Encounter
     * @return Boolean
     * TRUE if there is a deleted record; else FALSE
     * @author Jolly Caralos
     */
    public function removeEncounterInsurance(Encounter $encounter) {
        $insuranceProvider = InsuranceProvider::getProviderByShortFirmId(InsuranceProvider::INSURANCE_PHIC);
        $this->encounter_nr = $encounter->encounter_nr;
        $encounterInsurance = $this->getEncounterInsuranceByProvider($insuranceProvider);
        if(!empty($encounterInsurance)) {
            $encounterInsurance->delete();
            return true;
        }
        return false;
    }

}