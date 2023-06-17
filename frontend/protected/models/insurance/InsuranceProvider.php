<?php

namespace SegHis\models\insurance;

/**
 * This is the model class for table "care_insurance_firm".
 *
 * The followings are the available columns in table 'care_insurance_firm':
 * @property string $hcare_id
 * @property string $firm_id
 * @property string $name
 * @property string $iso_country_id
 * @property string $sub_area
 * @property integer $type_nr
 * @property string $accreditation_no
 * @property string $employer_no
 * @property string $addr
 * @property string $addr_mail
 * @property string $addr_billing
 * @property string $addr_email
 * @property string $phone_main
 * @property string $phone_aux
 * @property string $fax_main
 * @property string $fax_aux
 * @property string $contact_person
 * @property string $contact_phone
 * @property string $contact_fax
 * @property string $contact_email
 * @property string $use_frequency
 * @property string $status
 * @property string $history
 * @property string $modify_id
 * @property string $modify_time
 * @property string $create_id
 * @property string $create_time
 * @property integer $is_rvuadjustbasis
 * @property string $default_classification
 *
 * The followings are the available model relations:
 */
class InsuranceProvider extends \CareActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'care_insurance_firm';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('firm_id, accreditation_no, employer_no, history, modify_time, default_classification', 'required'),
			array('type_nr, is_rvuadjustbasis', 'numerical', 'integerOnly'=>true),
			array('firm_id', 'length', 'max'=>40),
			array('name, sub_area, addr_email, contact_person, contact_email', 'length', 'max'=>60),
			array('iso_country_id', 'length', 'max'=>3),
			array('accreditation_no', 'length', 'max'=>12),
			array('employer_no', 'length', 'max'=>15),
			array('addr', 'length', 'max'=>255),
			array('addr_mail, addr_billing', 'length', 'max'=>200),
			array('phone_main, phone_aux, fax_main, fax_aux, contact_phone, contact_fax, modify_id, create_id', 'length', 'max'=>35),
			array('use_frequency', 'length', 'max'=>20),
			array('status', 'length', 'max'=>25),
			array('default_classification', 'length', 'max'=>5),
			array('create_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('hcare_id, firm_id, name, iso_country_id, sub_area, type_nr, accreditation_no, employer_no, addr, addr_mail, addr_billing, addr_email, phone_main, phone_aux, fax_main, fax_aux, contact_person, contact_phone, contact_fax, contact_email, use_frequency, status, history, modify_id, modify_time, create_id, create_time, is_rvuadjustbasis, default_classification', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'hcare_id' => 'Hcare',
			'firm_id' => 'Firm',
			'name' => 'Name',
			'iso_country_id' => 'Iso Country',
			'sub_area' => 'Sub Area',
			'type_nr' => 'Type Nr',
			'accreditation_no' => 'Accreditation No',
			'employer_no' => 'Employer No',
			'addr' => 'Addr',
			'addr_mail' => 'Addr Mail',
			'addr_billing' => 'Addr Billing',
			'addr_email' => 'Addr Email',
			'phone_main' => 'Phone Main',
			'phone_aux' => 'Phone Aux',
			'fax_main' => 'Fax Main',
			'fax_aux' => 'Fax Aux',
			'contact_person' => 'Contact Person',
			'contact_phone' => 'Contact Phone',
			'contact_fax' => 'Contact Fax',
			'contact_email' => 'Contact Email',
			'use_frequency' => 'Use Frequency',
			'status' => 'Status',
			'history' => 'History',
			'modify_id' => 'Modify',
			'modify_time' => 'Modify Time',
			'create_id' => 'Create',
			'create_time' => 'Create Time',
			'is_rvuadjustbasis' => 'Is Rvuadjustbasis',
			'default_classification' => 'Default Classification',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InsuranceProvider the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->name;
    }
}
