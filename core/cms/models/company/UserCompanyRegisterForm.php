<?php


class UserCompanyRegisterForm extends CFormModel {
    
    public $username;
    public $email;
    public $password;
    public $verifyPassword;
    public $firstname;
    public $lastname;
    
    public $companyname;
    public $companytype;
    public $vat_code;
    public $region_id;
    public $province_id;
    public $location;
    public $adress;
    public $postal_code;
    public $phone;
    public $terms;
    public $bank_name;
    public $bank_iban;
    public $email_news;
    
    
    public $verifyCode;
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
        $passwordrule = array_merge(array('password', 'PasswordValidator', 'on' => 'step1'), $passwordRequirements);
        return array(
            array('username, email, password, verifyPassword, firstname, lastname, terms, verifyCode,
                location, adress, companyname, companytype, phone, vat_code, postal_code, bank_name, bank_iban', 'required'),
            array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => t('cms', 'Invalid username')),
            // email need to be email style
            array('email', 'email',  'message' => t('cms', 'Email is not valid')),
            array('email', 'unique',
                'attributeName' => 'email',
                'className' => 'cms.models.user.User',
                'message' => t('cms', 'This email has been registered.')),
            array('username', 'length', 'max'=>30,'min'=>3),
            array('username', 'unique',
                'attributeName' => 'username',
                'className' => 'cms.models.user.User',
                'message' => t('cms', 'Username has been registered.')),
            
           array('firstname, lastname, bank_name, bank_iban', 'length', 'max' => 50),
           array('location, adress, companyname', 'length', 'max' => 100),
           array('vat_code', 'length', 'is' => 11,  'message' => t('cms', '{attribute} is not correct')),
           array('vat_code, postal_code, phone', 'numerical', 'integerOnly' => true,
                'message' => t('cms', '{attribute} must contain digits only')),
           array('postal_code', 'length', 'is' =>5, 'message' => t('cms', '{attribute} is not correct')),
           array('region_id, province_id, companytype', 'numerical', 'integerOnly' => true, 'message' => t('cms', 'Please select the {attribute}')),

            $passwordrule,
            array('verifyPassword', 'compare', 'compareAttribute'=>'password'),
            
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
            'region_id' => t('cms', 'Region'),
            'province_id' => t('cms', 'Province'),
            'location' => t('cms', 'Location'),
            'adress' => t('cms', 'Adress'),
            'postal_code' => t('cms', 'Postal Code'),
            'phone' => t('cms', 'Phone'),
            'bank_name' => t('cms', 'Bank Name'),
            'bank_iban' => t('cms', 'IBAN Code'),
            'companyname' => t('cms', 'Company Name'),
            'vat_code' => t('cms', 'VAT Code'),
            'email_news' => t('cms', 'Newsletter'),
            'terms' => t('cms', 'Terms')
        );
    }
    
     public function checkLink($attribute, $params) {

        if (strpos($this->webcms, "http://") !== false) {
                $this->addError($attribute, t('cms','Your link is wrong, must be like www.mywebcms.com'));
                //return false;
        }
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
