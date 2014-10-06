<?php

/**
 * This is the model class for Create User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.user
 *
 */
class UserCompanyCreateForm extends CFormModel
{
    
        public $username;
        public $companyname;
        public $password;
        public $verifyPassword;
        public $email;
        public $membership_type;
        public $passwordRequirements = array(
			'minLen' => 8,
			'maxLen' => 32,
			'minLowerCase' => 1,
			'minUpperCase'=>0,
			'minDigits' => 1,
			'maxRepetition' => 3,
			);
        

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
            
            $passwordRequirements = $this->passwordRequirements;
		$passwordrule = array_merge(array(
					'password', 'PasswordValidator'), $passwordRequirements);
            return array(
                $passwordrule,
                array('username, companyname, email, password, verifyPassword', 'required'),              
                array('companyname', 'length','min'=>3, 'max'=>100),
                //array('password','length','min'=>8),
                array('email, username', 'length', 'max'=>128),
                array('email', 'email' , 'message'=>t('cms','Email is not valid')),
                array('email', 'unique',
                        'attributeName'=>'email',
                        'className'=>'cms.models.user.User',
                        'message'=>t('cms','This email has been registered.')),
                array('username', 'unique',
                        'attributeName'=>'username',
                        'className'=>'cms.models.user.User',
                        'message'=>t('cms','Username has been registered.')),
                array('verifyPassword','checkNewPass'),
                array('membership_type','numerical', 'integerOnly' => true),
             );
	}

        
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>t('cms','Username'),
                        'companyname'=>t('cms','Company Name'),
                        'membership_type'=>t('cms','Membership Option'),
                        'password'=>t('cms','Password'),
                        'verifyPassword'=>t('cms','Confirm Password'),
                        'email'=>t('cms','Email')
		);
	}
        
        public function checkNewPass($attribute,$params)
	{
              if($this->password!==$this->verifyPassword){
                        $this->addError($attribute, t('cms','Confirm password is not correct!'));
                        return false;
              }
	      
	}
              

}