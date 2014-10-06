<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class CompanyCreateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {

        $model = new UserCompanyCreateForm;
        $cprofile = new UserCompanyProfile;
        $csettings = new UserCompanySettings;
        $membershipinfo = new MembershipInfo;
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'company-create-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $model->membership_type = '0';
        // collect user input data
        if (isset($_POST['UserCompanyCreateForm'])) {
            $model->attributes = $_POST['UserCompanyCreateForm'];

            // validate user input password
            if ($model->validate()) {
                $new_comp = new User;
                $new_comp->username = $model->username;
                $new_comp->email = $model->email;
                $new_comp->password = $model->password;
                $new_comp->display_name = $model->username;
                $new_comp->user_type = ConstantDefine::USER_COMPANY;
                $new_comp->status = ConstantDefine::USER_STATUS_ACTIVE;
                
                if ($new_comp->save()) {
                        if ($model->membership_type !== '0') {
                            $membershipinfo->user_id = $new_comp->user_id;
                            $membershipinfo->membership_id = $_POST['UserCompanyCreateForm']['membership_type'];
                            $membershipinfo->payment_date = time();
                            //$membershipinfo->end_date = time() + ($membershipinfo->membership->duration * 86400);
                            $membershipinfo->save(array('user_id', 'membership_id'), false);
                        }
                    if (isset($cprofile)) {
                        $cprofile->companyId = $new_comp->user_id;
                        $cprofile->companyname = $model->companyname;
                        $cprofile->save(array('companyId', 'companyname'), false);
                    }
                    if (isset($csettings)) {
                        $csettings->companyId = $new_comp->user_id;
                        $csettings->save(false);
                    }
                    if ($model->membership_type == 0) {
                        $authorizer = Yii::app()->getModule("rights")->authorizer;
                        $authorizer->authManager->assign('Authenticated', $new_comp->user_id);
                    }

                    user()->setFlash('success', Yii::t('AdminUser', 'New company has beed successfully created.'));
                }
                $model = new UserCompanyCreateForm;
                Yii::app()->controller->redirect(array('view', 'id' => $new_comp->user_id));
            }
        }

        $this->render('cmswidgets.views.company.company_create_widget', array('model' => $model));
    }

}
