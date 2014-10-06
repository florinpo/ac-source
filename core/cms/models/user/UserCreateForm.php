<?php

/**
 * This is the model class for Create User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.user
 *
 */
class UserCreateForm extends CFormModel
{
    
        public $username;
        public $display_name;
        public $password;
        public $verifyPassword;
        public $email;
        public $user_type;
        
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
                array('username, display_name, email, password, verifyPassword', 'required'),              
                array('display_name', 'length', 'max'=>255),
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
                array('user_type', 'in', 'range' => array('normal', 'membership')),
             );
	}

        
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>Yii::t('User','Username'),
                        'display_name'=>Yii::t('User','Display Name'),
                        'password'=>Yii::t('User','Password'),
                        'verifyPassword'=>Yii::t('User','Confirm Password'),
                        'email'=>Yii::t('User','Email'),
                        'user_type'=>Yii::t('User','User Type') 
		);
	}
        
        public function checkNewPass($attribute,$params)
	{
              if($this->password!==$this->verifyPassword){
                        $this->addError($attribute,Yii::t('User','Confirm password is not correct!'));
                        return false;
              }
	      
	}
              

}