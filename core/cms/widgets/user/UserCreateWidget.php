<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class UserCreateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {

        $model = new UserCreateForm;
        $profile = new UserProfile;
        $settings = new UserSettings;
        $model->user_type = 'normal'; // in case we use membership
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-create-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['UserCreateForm'])) {
            $model->attributes = $_POST['UserCreateForm'];

            // validate user input password
            if ($model->validate()) {
                $new_user = new User;
                $new_user->username = $model->username;
                $new_user->email = $model->email;
                $new_user->display_name = $model->display_name;
                $new_user->password = $model->password;
                $new_user->user_type = ConstantDefine::USER_NORMAL;
                $new_user->status = ConstantDefine::USER_STATUS_ACTIVE;

                if ($new_user->save()) {
                    if (isset($profile)) {
                        $profile->userId = $new_user->user_id;
                        $profile->save(false);
                    }
                    if (isset($settings)) {
                        $settings->userId = $new_user->user_id;
                        $settings->save(false);
                    }

                    if (isset($_POST['roles_type'])) {
                        foreach ($_POST['roles_type'] as $selectedItem) {
                            if ($selectedItem) {
                                $authorizer = Yii::app()->getModule("rights")->authorizer;
                                $authorizer->authManager->assign($selectedItem, $new_user->user_id);
                            }
                        }
                    } else {
                        $authorizer = Yii::app()->getModule("rights")->authorizer;
                        $authorizer->authManager->assign('Authenticated', $new_user->user_id);
                    }
                    
                    user()->setFlash('success', Yii::t('AdminUser', 'New user has beed successfully created.'));
                }

                $model = new UserCreateForm;
                Yii::app()->controller->redirect(array('view', 'id' => $new_user->user_id));
            }
        }

        $this->render('cmswidgets.views.user.user_create_widget', array('model' => $model));
    }

}
