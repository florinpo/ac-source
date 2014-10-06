<?php

class UserRegisterForm extends CFormModel {

    public $username;
    public $email;
    public $password;
    public $verifyPassword;
    public $firstname;
    public $lastname;

    public $terms;
    public $email_news;
    public $verifyCode;
    public $passwordRequirements = array(
        'minLen' => 6,
        'maxLen' => 15,
        'minLowerCase' => 1,
        'minUpperCase' => 0,
        'minDigits' => 1,
        'maxRepetition' => 3,
    );

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {

        $passwordRequirements = $this->passwordRequirements;
        $passwordrule = array_merge(array('password', 'PasswordValidator'), $passwordRequirements);
        return array(
            array('username, email, password, verifyPassword, firstname, lastname, verifyCode', 'required'),
            // email need to be email style
            array('email', 'email', 'message' => t('cms', 'Email is not valid')),
            array('email', 'unique',
                'attributeName' => 'email',
                'className' => 'cms.models.user.User',
                'message' => t('cms', 'This email has been registered.')),
            array('username', 'length', 'max' => 20, 'min' => 3),
            array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => t('cms', 'Invalid username')),
            array('username', 'unique',
                'attributeName' => 'username',
                'className' => 'cms.models.user.User',
                'message' => t('cms', 'Username has been registered.')),
            array('firstname, lastname', 'length', 'max' => 50),
            $passwordrule,
            array('verifyPassword', 'compare', 'compareAttribute' => 'password'),
            array('terms', 'compare', 'compareValue' => true,
                'message' => t('cms', 'You must agree with the terms and conditions')),
            array('email_news', 'in', 'range' => array('0', '1')),
           
           array('verifyCode','captcha','captchaAction'=>'page/captcha','skipOnError'=>true),
            
        );
    }

    public function attributeLabels() {
        return array(
            'username' => t('cms', 'Username'),
            'email' => t('cms', 'Email'),
            'password' => t('cms', 'Password'),
            'verifyPassword' => t('cms', 'Password confirm'),
            'firstname' => t('cms', 'First name'),
            'lastname' => t('cms', 'Last name'),
            'email_news' => t('cms', 'Newsletter'),
            'terms' => t('cms', 'Terms'),
        );
    }

    /**
     * Function to Register user information
     * @return type 
     */
    public function doSignUp() {
        if (!$this->hasErrors()) {
            $newUser = new User;

            $newUser->password = $this->password;

            if (!$newUser->save()) {
                $this->addError('email', t('cms', 'Something is wrong with the Registration Process. Please try again later!'));
                return false;
            } else {
                //We can start to add Profile record here                            				
                //We can start to add User Activity here
                //We can check to send Email or not   
                //Create new UserLoginForm
                $login_form = new UserLoginForm();
                $login_form->username = $newUser->username;
                $login_form->password = $this->password;
                return $login_form->login();
            }
        }
    }

}
