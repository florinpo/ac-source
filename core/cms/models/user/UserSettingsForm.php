<?php

/**
 * This is the model class for Basic Settings Form.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.user
 *
 */
class UserSettingsForm extends CFormModel
{
        
         public $email_news;
         public $email_message;
         public $email_public;
        
        

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules() {
        return array(
           array('email_news, email_message, email_public', 'in', 'range' => array('0', '1')),
            
        );
    }
        
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email_news' => t('cms', 'Email news'),
                        'email_message' => t('cms', 'Email message'),
                        'email_public' => t('cms', 'Email Traffic'),          
		);
	}
}