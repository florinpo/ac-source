<?php

/**
 * This is the Widget for User update his own settings.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class UserEditProfileWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $user_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($user_id!=0) {
           
            $user = User::model()->findByPk($user_id);
            $model = new UserProfileForm;
            $profile = UserProfile::model()->find(array('condition'=>'userId=:userId', 'params'=>array(':userId'=>$user_id)));
            
            //Set basic info for Current user
            //Get the user by current Id
            if ($profile) {
                $model->firstname = !empty($profile->firstname) ? $profile->firstname : '';
                $model->lastname = !empty($profile->lastname) ? $profile->lastname : '';
                $model->gender = !empty($profile->gender) ? $profile->gender : '';
                $model->birthday = !empty($profile->birthday) ? $profile->birthday : '';
                $model->region_id = !empty($profile->region_id) ? $profile->region_id : '';
                $model->province_id = !empty($profile->province_id) ? $profile->province_id : '';
                $model->location = !empty($profile->location) ? $profile->location : '';
                $model->adress = !empty($profile->adress) ? $profile->adress : '';
                $model->phone = !empty($profile->adress) ? $profile->adress : '';
            } else {
                throw new CHttpException('503', Yii::t('error','User is not valid'));
            }


            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'profile-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['UserProfileForm'])) {

                $model->attributes = $_POST['UserProfileForm'];
                // validate user input and redirect to the previous page if valid                            
                if ($model->validate()) {
                    $profile->firstname = $model->firstname;
                    $profile->lastname = $model->lastname;
                    $profile->gender = $model->gender;
                    $profile->birthday = $model->birthday;
                    $profile->region_id = $model->region_id;
                    $profile->province_id = $model->province_id;
                    $profile->location = $model->location;
                    $profile->adress = $model->adress;
                    $profile->phone = $model->phone;
                    $profile->save();
                    user()->setFlash('success', Yii::t('AdminUser','User profile was successfully updated!'));
                }
            }

            $this->render('cmswidgets.views.user.user_edit_profile_widget', array('model' => $model, 'user'=>$user));
        } else {
            //Yii::app()->request->redirect(user()->returnUrl);
        }
    }

}
