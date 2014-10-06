<?php

/**
 * This is the model class for Changing User Profile.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.user
 *
 */
class MemberProfileForm extends CFormModel {
    
    public $firstname;
    public $lastname;
    public $companyposition;
    public $companyname;
    public $companytype;
    public $vat_code;
    public $region_id;
    public $province_id;
    public $domain_id;
    public $location;
    public $adress;
    public $postal_code;
    public $mobile;
    public $fax;
    public $phone;
    public $website;
    
    
    

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {

        return array(
            array('firstname, lastname, companyname, companytype, domain_id, vat_code, 
                region_id, province_id, location, phone, adress', 'required'),
            array('firstname, lastname', 'length', 'max' => 50),
            array('location, companyname, adress', 'length', 'max' => 100),
             array('mobile', 'length', 'is' => 10, 'message' => t('site', '{attribute} is not correct')),
            array('vat_code', 'length', 'is' => 11, 'message' => t('site', '{attribute} is not correct')),
            array('vat_code, postal_code, phone, fax, mobile', 'numerical', 'integerOnly' => true,
                'message' => t('site', '{attribute} must contain digits only')),
            array('postal_code', 'length', 'is' => 5, 'message' => t('site', '{attribute} is not correct')),
            array('region_id, province_id, companytype, companyposition', 'numerical', 'integerOnly' => true, 'message' => t('site', 'Please select the {attribute}')),
             array('website', 'length', 'max' => 512),
             array('website', 'checkLink'),

        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'firstname' => t('site','First Name'),
            'lastname' => t('site','Last Name'),
            'companyposition' => t('site','Company Position'),
            'companyname' => t('site', 'Company Name'),
            'domain_id' => t('site', 'Domain'),
            'vat_code' => t('site', 'V.A.T. Code'),
            'companytype' => t('site', 'Company Type'),
            'companyposition' => t('site', 'Company Position'),
            'region_id' => t('site', 'Region'),
            'province_id' => t('site', 'Province'),
            'location' => t('site', 'Location'),
            'adress' => t('site', 'Adress'),
            'postal_code' => t('site', 'Postal Code'),
            'phone' => t('site', 'Phone number'),
            //'email_alert' => t('site', 'Email alert'),
        );
    }
     public function checkLink($attribute, $params) {

        if (strpos($this->website, "http://") !== false) {
                $this->addError($attribute, t('site','Your link is wrong, must be like www.mywebsite.com'));
                //return false;
        }
    }


}