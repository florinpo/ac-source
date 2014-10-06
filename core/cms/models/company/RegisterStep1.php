<?php

class RegisterStep1 extends CFormModel {

    public $username;
    public $email;
    public $password;
    public $verifyPassword;
    public $firstname;
    public $lastname;
    public $companyposition;
    public $terms;
    public $email_news;
    //public $passwordRequirements;
    public $passwordRequirements = array(
        'minLen' => 8,
        'maxLen' => 32,
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
            array('username, email, password, verifyPassword, firstname, lastname, companyposition, terms', 'required'),
            array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => Yii::t('FrontendUser', 'Invalid username')),
            // email need to be email style
            array('email', 'email', 'on' => 'step1', 'message' => Yii::t('FrontendUser', 'Email is not valid')),
            array('email', 'unique',
                'attributeName' => 'email',
                'className' => 'cms.models.user.User',
                'message' => Yii::t('FrontendUser', 'This email has been registered.')),
            array('username', 'unique',
                'attributeName' => 'username',
                'className' => 'cms.models.user.User',
                'message' => Yii::t('FrontendUser', 'Username has been registered.')),
            array('firstname, lastname', 'length', 'max' => 50),
            $passwordrule,
            array('verifyPassword', 'compare', 'compareAttribute' => 'password'),
            array('terms', 'compare', 'compareValue' => true,
                'message' => Yii::t('FrontendUser', 'You must agree with the terms and conditions')),
            array('email_news', 'in', 'range' => array('0', '1')),
                //array('verifyCode', 'CaptchaExtendedValidator', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        );
    }

    public function attributeLabels() {
        return array(
            'username' => Yii::t('FrontendUser', 'Username'),
            'email' => Yii::t('FrontendUser', 'Email'),
            'password' => Yii::t('FrontendUser', 'Password'),
            'verifyPassword' => Yii::t('FrontendUser', 'Password confirm'),
            'firstname' => Yii::t('FrontendUser', 'First name'),
            'lastname' => Yii::t('FrontendUser', 'Last name'),
            'companyposition' => Yii::t('FrontendUser', 'Position (within the company)'),
            'email_news' => Yii::t('FrontendUser', 'Newsletter'),
            'terms' => Yii::t('FrontendUser', 'Terms'),
        );
    }

    public function getForm() {
		return new CForm(array(
			//'showErrorSummary'=>true,
			'elements'=>array(
				'username'=>array(
					'hint'=>'6-12 characters; letters, numbers, and underscore'
				),
				'password'=>array(
					'type'=>'password',
					'hint'=>'8-12 characters; letters, numbers, and underscore; mixed case and at least 1 digit',
				),
				'password_repeat'=>array(
					'type'=>'password',
					'hint'=>'Re-type your password',
				),
				'email'=>array(
					'hint'=>'Your e-mail address'
				)
			),
			'buttons'=>array(
				'submit'=>array(
					'type'=>'submit',
					'label'=>'Next'
				),
				'save_draft'=>array(
					'type'=>'submit',
					'label'=>'Save'
				)
			)
		), $this);
	}

}
