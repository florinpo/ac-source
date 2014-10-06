<?php

class PaymentInfoForm extends CFormModel {

    public $last_name;
    public $item;
    public $first_name;
    public $email;
    public $company_name;
    public $company_position;
    public $vat_code;
    public $bank_name;
    public $bank_number;
    public $region_id;
    public $province_id;
    public $location;
    public $adress;
    public $postal_code;
    public $phone;
    public $fax;
    public $mobile;

    public function rules() {
        return array(
            array('item, last_name, first_name, email, company_name, company_position, vat_code,
                  bank_name, bank_number, location, adress, adress, postal_code, phone', 'required'),
            array('email', 'email', 'message' => t('site', 'Email is not valid')),
            array('first_name, last_name', 'length', 'max' => 50),
            array('location, adress, company_name, bank_name, bank_number', 'length', 'max' => 100),
            array('phone', 'numerical', 'integerOnly' => true, 'message' => t('site', '{attribute} must contain digits only')),
            array('vat_code, postal_code, phone, fax, mobile', 'numerical', 'integerOnly' => true,
                'message' => t('site', '{attribute} must contain digits only')),
            array('region_id, province_id', 'numerical', 'integerOnly' => true, 'message' => t('site', 'Please select the {attribute}')),
            array('postal_code', 'length', 'is' => 5, 'message' => t('site', '{attribute} is not correct')),

        );
    }
    
    public function attributeLabels() {
        return array(
            'firstname' => t('site', 'First name'),
            'lastname' => t('site', 'Last name'),
            'email' => t('site', 'Email'),
            'region_id' => t('site', 'Region'),
            'province_id' => t('site', 'Province'),
            'location' => t('site', 'Location'),
            'adress' => t('site', 'Adress'),
            'postal_code' => t('site', 'Postal Code'),
            'phone' => t('site', 'Phone'),
            'mobile' => t('site', 'Mobile'),
            'fax' => t('site', 'Fax'),
            'company_name' => t('site', 'Company Name'),
            'company_position' => t('site', 'Position (within the company)'),
            'vat_code' => t('site', 'VAT Code'),
            'bank_name' => t('site', 'Bank name'),
            'bank_number' => t('site', 'Bank account'),
        );
    }

}