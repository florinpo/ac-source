<?php

/**
 * This is the model class for Changing User Profile.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.user
 *
 */
class UserProfileForm extends CFormModel {

    public $firstname;
    public $lastname;
    public $gender;
    public $birthday;
    public $region_id;
    public $province_id;
    public $location;
    public $phone;
    public $avatar;
    

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {

        return array(
            array('firstname, lastname, region_id, province_id', 'required'),
            
            array('gender', 'required', 'message' => t('cms','Please select your gender')),
            array('firstname, lastname', 'length', 'max' => 50),
            array('location', 'length', 'max' => 100),
            array('birthday', 'BirthdayValidate', 'minAge'=>18, 'maxAge'=>120, 'allowEmpty'=>false),
            array('region_id, province_id', 'numerical', 'integerOnly' => true, 'message' => t('cms','Please select the {attribute}')),
            array('phone', 'numerical', 'integerOnly' => true),
            array('phone', 'length', 'max' => 20),
            array('avatar', 'file', 'allowEmpty' => true)
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'firstname' => t('cms','First Name'),
            'lastname' => t('cms','Last Name'),
            'gender' => t('cms','Gender'),
            'birthday' => t('cms','Date of birth'),
            'region_id' => t('cms','Region'),
            'province_id' => t('cms','Province'),
            'location' => t('cms','Location'),
            'phone' => t('cms','Phone number')
        );
    }


}