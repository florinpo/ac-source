<?php

/**
 * This is the model class for Changing FrontendUser Profile.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.FrontendUser
 *
 */
class UserCompanyProfileForm extends CFormModel {

    
    
    public $description;
    public $logo;
    public $uploadimg;
    public $selected_cats = array();
    public $services;
    public $certificate;
    public $marketplace;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {

        $purifier = new CHtmlPurifier();
        $purifier->options = array('HTML.Allowed' => '',);


        return array(
            array('description, services', 'required'),
            array('selected_cats', 'required', 'message' => t('site', 'Please select at least one category')),
            array('description', 'length', 'min' => 200, 'max' => 800),
            array('services, certificate', 'length', 'max' => 200),
            array('description, services', 'filter', 'filter' => array($purifier, 'purify')),
            array('logo', 'safe'),
            array('marketplace', 'numerical', 'integerOnly' => true, 'message' => t('site', 'Please select the {attribute}')),
            array('uploadimg', 'file', 'allowEmpty' => true)
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'website' => t('site', 'Website'),
            'description' => t('site', 'Description'),
            'uploadimg' => t('site', 'Company Logo'),
            'selected_cats' => t('site', 'Selected categories'),
            'services' => t('site', 'Product and services'),
            'certificate' => t('site', 'Certificates'),
        );
    }
   

}