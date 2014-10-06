<?php

/**
 * This is the model class for Change Password Form.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.user
 *
 */
class UserChangePassForm extends CFormModel {

    public $old_password;
    public $new_password_1;
    public $new_password_2;
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
        $passwordrule = array_merge(array(
            'new_password_1', 'PasswordValidator'), $passwordRequirements);
        return array(
            array('old_password, new_password_1, new_password_2', 'required'),
            $passwordrule,
            array('old_password', 'checkOldPass'),
            array('new_password_1', 'checkNewPass'),
            array('new_password_2', 'compare', 'compareAttribute' => 'new_password_1'),
        );
    }

    /**
     * Check the old pass is Ok or not
     * 
     * @param array $attribute
     * @param array $params
     * @return boolean 
     */
    public function checkOldPass($attribute, $params) {
        $u = User::model()->findbyPk(user()->id);
        if ($u != null) {
            
            if (!$u->verifyPassword($this->old_password)) {
                $this->addError($attribute, t('cms', 'Old password is not correct!'));
                return false;
            }
        } else {
            $this->addError($attribute, t('cms', 'No User Found!'));
            return false;
        }
    }

    /**
     * Compare the two new password match or not
     * @param array $attribute
     * @param array $params
     * @return boolean
     */
    public function checkNewPass($attribute, $params) {
        $u = User::model()->findbyPk(user()->id);
        if($u->verifyPassword($this->new_password_1)){
            $this->addError($attribute, t('cms', 'The new password can\'t be the same as old password'));
            return false;
        }
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'old_password' => t('cms', 'Vecchia password'),
            'new_password_1' => t('cms', 'Nuova password'),
            'new_password_2' => t('cms', 'Conferma nuova password')
        );
    }

}