<?php

/**
 * This is the Widget for Updating User Information.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class CompanyUpdateWidget extends CWidget {

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

            $membershipinfo = MembershipInfo::model()->findByAttributes(array('user_id' => $user_id));

            $old_pass = (string) $model->password;
            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'company-update-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            // collect user input data
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->password != $old_pass) {
                    $model->password = $model->hashPassword($model->password, USER_SALT);
                }
                // we check if the user has already membership
                if (isset($membershipinfo)) {

                    if ($_POST['membership_type'] !== '0') {
                        $membershipinfo->membership_id = $_POST['membership_type'];
                        if ($membershipinfo->save(false)) {
                            //if user has membership we clear the roles
                            $selectedroles = Rights::getAssignedRoles($model->user_id);
                            $select = array();
                            foreach ($selectedroles as $r) {
                                array_push($select, $r->name);
                                $authorizer = Yii::app()->getModule("rights")->authorizer;
                                $authorizer->authManager->revoke($r->name, $model->user_id);
                            }
                            $m_id = (int)$_POST['membership_type'];
                            $m_selected = MembershipItem::model()->findByPk($m_id);
                            $authorizer = Yii::app()->getModule("rights")->authorizer;
                            $authorizer->authManager->assign($m_selected->rolename, $model->user_id);
                        }
                        $model->has_membership='1';
                    }

                    if ($_POST['membership_type'] == '0') {
                        //if user has membership we clear the roles
                        //MembershipInfo::model()->deleteAllByAttributes(array('user_id' => $model->user_id));
                         MembershipInfo::model()->deleteAll(array('condition'=>'user_id=:userId', 'params'=>array(':userId'=>$model->user_id)));
                        $selectedroles = Rights::getAssignedRoles($model->user_id);
                        $select = array();
                        foreach ($selectedroles as $r) {
                            array_push($select, $r->name);
                            $authorizer = Yii::app()->getModule("rights")->authorizer;
                            $authorizer->authManager->revoke($r->name, $model->user_id);
                        }
                        // and apply only authenticated role
                        $authorizer = Yii::app()->getModule("rights")->authorizer;
                        $authorizer->authManager->assign('Authenticated', $model->user_id);
                        $model->has_membership='0';
                    }
                } else {
                    // if the user does not have membership we create one
                    if (!empty($_POST['membership_type']) && $_POST['membership_type'] !== '0') {
                        $membership = new MembershipInfo;
                        $membership->user_id = $model->user_id;
                        $membership->membership_id = $_POST['membership_type'];
                        $membership->payment_date = time();
                        //$membershipinfo->end_date = time() + ($membershipinfo->membership->duration * 86400);
                        $membership->save(array('user_id', 'membership_id'), false);

                        //if user has membership we clear the roles
                        $selectedroles = Rights::getAssignedRoles($model->user_id);
                        $select = array();
                        foreach ($selectedroles as $r) {
                            array_push($select, $r->name);

                            $authorizer = Yii::app()->getModule("rights")->authorizer;
                            $authorizer->authManager->revoke($r->name, $model->user_id);
                        }

                        $m_id = $_POST['membership_type'];
                        if (isset($m_id)) {
                            $m_selected = MembershipItem::model()->findByPk($m_id);
                            $authorizer = Yii::app()->getModule("rights")->authorizer;
                            $authorizer->authManager->assign($m_selected->rolename, $model->user_id);
                        }
                        $model->has_membership='1';
                    }
                }
                $model->scenario='update';
                if ($model->save(false)) {

                    user()->setFlash('success', Yii::t('AdminUser', 'User Updated Successfully!'));
                }
            }

            $this->render('cmswidgets.views.company.company_update_widget', array('model' => $model));
        } else {
            throw new CHttpException(404, t('error', 'The requested page does not exist.'));
        }
    }

}
