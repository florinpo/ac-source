<?php

/**
 * This is the Widget for Updating User Information.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class UserUpdateWidget extends CWidget {

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

        if ($user_id !== 0) {
            $model = User::model()->findbyPk($user_id);

            $old_pass = (string) $model->password;
            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-update-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            // collect user input data
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->password != $old_pass) {
                    $model->password = $model->hashPassword($model->password, USER_SALT);
                }
                // we apply roles
                if (isset($_POST['roles_type'])) {
                    
                    foreach ($_POST['roles_type'] as $selectedItem) {
                        if ($selectedItem) {
                            $authorizer = Yii::app()->getModule("rights")->authorizer;
                            $authorizer->authManager->assign($selectedItem, $model->user_id);
                        }
                    }

                    $all_roles = new RAuthItemDataProvider('roles', array('type' => 2));
                    $data = $all_roles->fetchData();

                    $s = array();
                    foreach ($data as $r) {
                        array_push($s, $r->name);
                    }

                    $unselected = array_diff($s, $_POST['roles_type']);

                    foreach ($unselected as $role) {
                        if (!in_array($role, $_POST['roles_type'])) {
                            $authorizer = Yii::app()->getModule("rights")->authorizer;
                            $authorizer->authManager->revoke($role, $model->user_id);
                        }
                    }
                    
                } else if (empty($_POST['roles_type'])) {
                    $roles = Rights::getAssignedRoles($model->user_id);
                    foreach ($roles as $role) {
                        $authorizer = Yii::app()->getModule("rights")->authorizer;
                        $authorizer->authManager->revoke($role->name, $model->user_id);
                    }
                }
                $model->scenario='update';
                if ($model->save()) {
                    user()->setFlash('success', Yii::t('AdminUser', 'User Updated Successfully!'));
                }
            }

            $this->render('cmswidgets.views.user.user_update_widget', array('model' => $model));
        } else {
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        }
    }

}
