<?php


class RegisterStep2 extends CFormModel {
  
    public $companyname;
    public $companytype;
    
    public $vat_code;
    public $website;
    public $description;
    
    public $region_id;
    public $province_id;
    public $location;
    public $adress;
    public $postal_code;
    public $mobile;
    public $fax;
    public $phone;

    
    public function rules() {

        
        return array(
            array('location, adress, companyname, companytype, phone, description, vat_code', 'required'),
            
           array('location, adress, companyname', 'length', 'max' => 100),
           array('description', 'length', 'min' => 200, 'max' =>800),
           array('mobile', 'length', 'is' => 10, 'message' => Yii::t('FrontendUser', '{attribute} is not correct')),
           array('vat_code', 'length', 'is' => 11,  'message' => Yii::t('FrontendUser', '{attribute} is not correct')),
           array('vat_code, postal_code, phone, fax, mobile', 'numerical', 'integerOnly' => true,
                'message' => Yii::t('FrontendUser', '{attribute} must contain digits only')),
           array('website', 'length', 'max'=>512,),
           array('website', 'checkLink'),
           array('postal_code', 'length', 'is' =>5, 'message' => Yii::t('FrontendUser', '{attribute} is not correct')),
           array('region_id, province_id', 'numerical', 'integerOnly' => true, 'message' => Yii::t('FrontendUser', 'Please select the {attribute}')),
            
        );
    }


    public function attributeLabels() {
        return array(
            'region_id' => Yii::t('FrontendUser', 'Region'),
            'province_id' => Yii::t('FrontendUser', 'Province'),
            'location' => Yii::t('FrontendUser', 'Location'),
            'adress' => Yii::t('FrontendUser', 'Adress'),
            'postal_code' => Yii::t('FrontendUser', 'Postal Code'),
            'phone' => Yii::t('FrontendUser', 'Phone'),
            'mobile' => Yii::t('FrontendUser', 'Mobile'),
            'fax' => Yii::t('FrontendUser', 'Fax'),
            'companyname' => Yii::t('FrontendUser', 'Company Name'),
            'companytype' => Yii::t('FrontendUser', 'Company Type'),
            'vat_code' => Yii::t('FrontendUser', 'VAT Code'),
            'description' => Yii::t('FrontendUser', 'Company Description'),
            'website' => Yii::t('FrontendUser', 'Company Website'),
        );
    }
    
     public function checkLink($attribute, $params) {

        if (strpos($this->website, "http://") !== false) {
                $this->addError($attribute, Yii::t('FrontendUser','Your link is wrong, must be like www.mywebsite.com'));
                //return false;
        }
    }

}
