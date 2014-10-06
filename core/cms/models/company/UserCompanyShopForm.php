<?php

/**
 * This is the model class for Changing FrontendUser Profile.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.FrontendUser
 *
 */
class UserCompanyShopForm extends CFormModel {

    public $description;
    public $logo;
    public $uploadimg;
    public $selected_cats=array();
    public $services;
    public $certificate;
    public $webcms;
    public $shipping_available;
    public $selected_shipopts = array();
    public $shipping_description;
    public $delivery_type;
    
    public $region_id;
    public $province_id;
    public $r_region_id;
    public $r_province_id;
    public $selected_provinces = array();

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        
        $purifier = new CHtmlPurifier();
        $purifier->options = array('HTML.Allowed' => 'p, strong, ul, li');
        
        $purifier2 = new CHtmlPurifier();
        $purifier2->options = array('HTML.Allowed' => '');


        return array(
            array('description, services, delivery_type', 'required'),
            array('selected_cats', 'required', 'message' => t('cms', 'Please select at least one category')),
            array('selected_provinces', 'validateRegional'),
            array('selected_shipopts', 'validateSopts'),
            array('description', 'length', 'min' => 200, 'max' => 1000),
            array('shipping_description', 'length', 'max' => 1000),
            array('services', 'length', 'max' => 200),
            array('province_id', 'validateLocal'),
            array('description, shipping_description', 'filter', 'filter' => array($purifier, 'purify')),
            array('services', 'filter', 'filter' => array($purifier2, 'purify')),
            //array('region_id, province_id', 'numerical', 'integerOnly' => true, 'message' => t('cms', 'Please select the {attribute}')),
            array('logo, region_id, province_id, r_region_id, r_province_id', 'safe'),
            array('shipping_available', 'in', 'range' => array('0', '1')),
            //array('marketplace', 'numerical', 'integerOnly' => true, 'message' => t('cms', 'Please select the {attribute}')),
            array('uploadimg', 'file', 'allowEmpty' => true)
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'description' => t('cms', 'Description'),
            'uploadimg' => t('cms', 'Company Logo'),
            'selected_cats' => t('cms', 'Selected categories'),
            'services' => t('cms', 'Product and services'),
            'certificate' => t('cms', 'Certificates'),
            'region_id' => t('cms', 'Region'),
            'province_id' => t('cms', 'Province'),
        );
    }

    public function validateSopts($attribute, $params) {
        if($this->shipping_available == 1 ) {
            if (empty($this->$attribute)) {
                // Checks for only when there is a value
                // @todo Add additional error checking here
                $this->addError($attribute,  t('cms', 'Please select at least one shipping option'));
            }
        } else if($this->shipping_available==0 && !empty($this->$attribute)){
            $this->addError($attribute,  t('cms', 'Please enable the shipping availability option'));
        }
    }
    
    public function validateLocal($attribute, $params) {
        if($this->delivery_type == ConstantDefine::DELIVER_OPTION_LOCAL) {
          
            if (empty($this->$attribute)) {
                // Checks for only when there is a value
                // Add additional error checking here
                $this->addError($attribute,  t('cms', '{attribute} cannot be empty', array('{attribute}'=>$this->getAttributeLabel($attribute))));
            }
        }
        return false;
    }
    public function validateRegional($attribute, $params) {
        if($this->delivery_type == ConstantDefine::DELIVER_OPTION_REGIONAL ) {
            $data = explode(',', $this->$attribute);
            if (count($data)<2) {
                // Checks for only when there is a value
                // Add additional error checking here
                $this->addError($attribute,  t('cms', 'Please select at least 2 location'));
            }
        }
    }
}