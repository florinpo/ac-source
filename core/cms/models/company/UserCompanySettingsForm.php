<?php

/**
 * This is the model class for Changing FrontendUser Profile.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.FrontendUser
 *
 */
class UserCompanySettingsForm extends CFormModel {
    
    
    //email_news, email_message, email_traffic, email_inquiry, email_status

    public $email_news;
    public $email_message;
    public $email_traffic;
    public $email_inquiry;
    public $email_status;
   

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
           array('email_news, email_message, email_traffic, email_inquiry, email_status', 'in', 'range' => array('0', '1')),
            
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'email_news' => t('cms', 'Email news'),
            'email_message' => t('cms', 'Email message'),
            'email_traffic' => t('cms', 'Email Traffic'),
            'email_inquiry' => t('cms', 'Email Inquiry'),
            'email_status' => t('cms', 'Email Status'),
        );
    }
    
}